#include <opencv2/core/core.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <iostream>


using namespace cv;
using namespace std;




/*  Cette fonction prend en paramètre un nombre d'images à traiter et leurs emplacements en mémoire,
    et les assemble en une seule grande image en les empilant l'une en dessous de l'autre.

	Arguments : - argv : même argv que celui de la fonction main, possédant donc l'emplacement des images à traiter.
				- numberOfImages : nombre d'images que le programme doit traiter, valant donc argc-2.

	Valeur de retour : un pointeur vers la grande image formée.
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
			cout << "L'image à l'adresse " << argv[i + 1] << " n'a pas pu être ouverte ou n'existe pas." << endl;
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




/*	Cette fonction découpe la grande image, obtenue en assemblant les images de départ, suivant les numéros de ligne spécifiés dans splitPoints.

	Arguments : - src : matrice représentant l'image à découper.
				- splitPoints : vecteur d'entier stockant les numéros des lignes selon lesquelles on veut découper l'image.

	Valeur de retour : vecteur stockant les images obtenues en découpant.
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





/*  Ce programme prend en entrée un ensemble d'images, les assemble en une seule grande image, puis la découpe en plusieurs nouvelles images
	selon un ensemble de coordonnées donné. Les images obtenus sont ensuite enregistrées dans le dossier spécifié au programme.
	Ce dossier ainsi que le nombre et l'emplacement des images sont spécifiés au programme via les arguments de la fonction main :
	
	- argc : nombre de chaînes de caractères stockées dans argv
	- argv : liste de chaînes de caractères où sont stockés les chemins d'accès aux images dans l'ordre d'assemblage voulu, ainsi que le dossier d'enregistrement.
			 les chemins d'accès sont passés en argument au programme par ligne de commande, donc :
				- argv[0] représente la commande qui a lancé le programme
				- argv[1]->argv[argc-2] représentent les chemins d'accès aux images
				- argv[argc-1] représente le chemin d'accès du dossier dans lequel les images obtenues à la fin du programme doivent être enregistrées
				- argv[argc] est le pointeur NULL
*/

int main(int argc, char** argv)
{
	if (argc < 3)
	{
		cout << "Veuillez exécuter ce programme en passant en argument tous les chemins d'accès aux images à traiter dans l'ordre, ainsi qu'un dernier argument correspondant au chemin d'accès du dossier dans lequel seront enregistrées les images obtenues après traitement." << endl;
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