#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <iostream>


using namespace cv;
using namespace std;




/*  Cette fonction prend en param�tre un nombre d'images � traiter et leurs emplacements en m�moire,
    et les assemble en une seule grande image en les empilant l'une en dessous de l'autre.

	Arguments : - argv : m�me argv que celui de la fonction main, poss�dant donc l'emplacement des images � traiter.
				- numberOfImages : nombre d'images que le programme doit traiter, valant donc argc-2.

	Valeur de retour : un pointeur vers la grande image form�e.
*/

Mat* assembleImages(char** argv, int numberOfImages)
{
	vector <Mat> images(numberOfImages);

	Mat* outputImage;   // Stores the resuting image 
	int biggestImageIndex(0);   // Stocks the index of the image which has the highest number of columns
	int biggestImageColsNumber(0);

	for (int i = 0; i < numberOfImages; i++)  // Read all the images. Their paths must be of the type "..._(number).(extension)" with number greater or equal to one.
	{
		images[i] = imread(argv[i + 1], IMREAD_COLOR);  // Loads the image in RGB

		if (!images[i].data)  // Check for invalid input
		{
			cout << "L'image � l'adresse " << argv[i + 1] << " n'a pas pu �tre ouverte ou n'existe pas." << endl;
			return NULL;
		}

		if (images[i].cols > biggestImageColsNumber)   // Check if we must actualize the biggest image
		{
			biggestImageColsNumber = images[i].cols;
			biggestImageIndex = i;
		}
	}

	outputImage = new Mat();

	Mat sizedCopy;  // Used if it is necessary to resize an image to the correct number of columns
	int left;   // Number of columns to add to the sides of the image that has to be resized
	int right;
	Scalar value = Scalar(255, 255, 255);   // Value of the pixel to use to fill the space when resizing
	for (int i = 0; i < numberOfImages; i++)
	{
		if (images[i].cols != biggestImageColsNumber)
		{
			left = floor((double)(biggestImageColsNumber - images[i].cols) / 2);
			right = ceil((double)(biggestImageColsNumber - images[i].cols) / 2);
			copyMakeBorder(images[i], sizedCopy, 0, 0, left, right, BORDER_CONSTANT, value);
			images[i] = sizedCopy;
		}

		if (i == 0)
		{
			images[i].copyTo(*outputImage);
		}

		else
		{
			outputImage->push_back(images[i]);
		}
	}

	return outputImage;
}




/*	Cette fonction d�coupe la grande image, obtenue en assemblant les images de d�part, suivant les num�ros de ligne sp�cifi�s dans splitPoints.

	Arguments : - src : matrice repr�sentant l'image � d�couper.
				- splitPoints : vecteur d'entier stockant les num�ros des lignes selon lesquelles on veut d�couper l'image.

	Valeur de retour : vecteur stockant les images obtenues en d�coupant.
*/

vector <Mat> splitImage(const Mat &src, vector <int> splitPoints)
{
	vector <int>::iterator it = splitPoints.begin();

	if (splitPoints[0] != 0)
	{
		splitPoints.insert(it, 0);
	}

	if (splitPoints[splitPoints.size() - 1] == src.cols)
	{
		splitPoints.pop_back();
	}

	vector <Mat> images(splitPoints.size());
	Mat subMatrix;
	
	for (int i = 0; i < splitPoints.size(); i++)
	{
		if (i != splitPoints.size() - 1)
		{
			subMatrix = src.rowRange(splitPoints[i], splitPoints[i + 1]);
			images[i] = subMatrix.clone();
		}

		else
		{
			subMatrix = src.rowRange(splitPoints[i], src.rows);
			images[i] = subMatrix.clone();
		}
	}

	return images;
}





/*  Ce programme prend en entr�e un ensemble d'images, les assemble en une seule grande image, puis la d�coupe en plusieurs nouvelles images
	selon un ensemble de coordonn�es donn�. Les images obtenus sont ensuite enregistr�es dans le dossier sp�cifi� au programme.
	Ce dossier ainsi que le nombre et l'emplacement des images sont sp�cifi�s au programme via les arguments de la fonction main :
	
	- argc : nombre de cha�nes de caract�res stock�es dans argv
	- argv : liste de cha�nes de caract�res o� sont stock�s les chemins d'acc�s aux images dans l'ordre d'assemblage voulu, ainsi que le dossier d'enregistrement.
			 les chemins d'acc�s sont pass�s en argument au programme par ligne de commande, donc :
				- argv[0] repr�sente la commande qui a lanc� le programme
				- argv[1]->argv[argc-2] repr�sentent les chemins d'acc�s aux images
				- argv[argc-1] repr�sente le chemin d'acc�s du dossier dans lequel les images obtenues � la fin du programme doivent �tre enregistr�es
				- argv[argc] est le pointeur NULL
*/

int main(int argc, char** argv)
{
	if (argc < 3)
	{
		cout << "Veuillez ex�cuter ce programme en passant en argument tous les chemins d'acc�s aux images � traiter dans l'ordre, ainsi qu'un dernier argument correspondant au chemin d'acc�s du dossier dans lequel seront enregistr�es les images obtenues apr�s traitement." << endl;
		return -1;
	}

	int const numberOfImages(argc - 2);
	Mat* outputImage = assembleImages(argv, numberOfImages);  // creation of the big image

	if (!outputImage)  // if the process failed
	{
		return -1;
	}

	vector <int> splitPoints(10);
	for (int i = 0; i < 10; i++)   // for the moment, the image is simply split in ten parts of the same size
	{
		splitPoints[i] = i*outputImage->rows / 10;
	}

	vector <Mat> images = splitImage(*outputImage, splitPoints);

	char path[sizeof(argv[argc - 1]) + 100];
	for (int i = 0; i < images.size(); i++)   // writing each image in the specified folder
	{
		sprintf(path, "%s%s%.2d%s", argv[argc - 1], "\\im_", (i + 1), ".jpg");
		imwrite(path, images[i]);
	}

	return 0;
}