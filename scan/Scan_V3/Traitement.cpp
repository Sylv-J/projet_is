#include "Traitement.h"

using namespace cv;


// Couleur theorique de la gomette
int h=60, HTolerance=10;
int s=255, STolerance=50;
int v=255, VTolerance=70;

void detectCircles(QImage *qim, QVector <int> *divisionPoints)
{
  h=h/2; //H 0->360 ,n'est informatiquement codé que entre 0 et 180
  cv::Scalar lowerBoundary(h-HTolerance, s-STolerance, v-VTolerance);
  cv::Scalar upperBoundary(h+HTolerance, s+STolerance, v+VTolerance);

  cv::Mat originalMat = QImageToCvMat(*qim);

  // Convert analyzedMat image to HSV
  cv::Mat hsv;
  cv::cvtColor(originalMat, hsv, cv::COLOR_BGR2HSV);
  // RGB : 255 255 255
  // HSV : 360° 255 255, mais 360 dépasse un bit, on utilise donc :
  // HSV : 180 255 255!!!!

  // nettoye l'image, on ne garde que les pixels selectionnés et on les enregistre dans analyzedMat
  cv::inRange(hsv, lowerBoundary, upperBoundary, originalMat);
  // attention le rouge s'étale avant 180 et après 0, il faudrait recopier cette ligne 2 fois pour l'identifier puis combiner l'image ensuite



  cv::Mat blackWhiteMat = originalMat.clone();





  std::vector< std::vector<cv::Point> > contours;
  std::vector<cv::Point> points;
  cv::findContours(originalMat, contours, CV_RETR_LIST, CV_CHAIN_APPROX_NONE);

  int minX[contours.size()], maxX[contours.size()], minY[contours.size()], maxY[contours.size()];
  for (size_t i=0; i<contours.size(); i++)
  {
    minX[i] = contours[i][0].x;
    maxX[i] = contours[i][0].x;
    minY[i] = contours[i][0].y;
    maxY[i] = contours[i][0].y;
    for (size_t j = 0; j < contours[i].size(); j++)
    {
      minX[i] = min(minX[i], contours[i][j].x);
      minY[i] = min(minY[i], contours[i][j].y);
      maxX[i] = max(maxX[i], contours[i][j].x);
      maxY[i] = max(maxY[i], contours[i][j].y);
    }
    // pour l'instant on utilise que max et min Y pour découper la page, max et min X pourront etre utilisées à l'avenir pour augmenter la précision de l'analyse
    rectangle(originalMat, Point(minX[i],minY[i]) , Point(maxX[i], maxY[i]), Scalar(255,0,0));
    if(isValable(minX[i], minY[i], maxX[i], maxY[i], blackWhiteMat))
    {
      // la ligne suivante doit être testée attentivement (interface entre le code d'illias et le mien, erreurs de conversions possibles.)
      divisionPoints->push_back((maxY[i]+minY[i])/2);
    }
  }

  std::sort(divisionPoints->begin(), divisionPoints->end());





  // namedWindow( "Display window", WINDOW_AUTOSIZE );// Create a window for display.1
  // imshow( "Display window", blackWhiteMat );                   // Show our image inside it.
}


bool isValable(int minX, int minY, int maxX, int maxY, cv::Mat mat)
{
  bool res=true;

  float diffX = maxX-minX;
  float diffY = maxY-minY;

  // largeur hauteur disproportionnés
  if(diffX==0 || diffY==0)
  {
    res=false;
  }
  else
  {
    if(!(diffX/diffY>1/1.2 && diffX/diffY<1.2/1))
    {
      res=false;
    }
    else
    {
      //test de l'aire
      cv::Rect new_size(minX, minY, maxX, maxY);
      cv::Rect imgBounds(0,0,mat.cols,mat.rows);
      new_size = new_size & imgBounds;
      // Now you can do the following without worrying
      cv::Mat around_gomette = mat(new_size);
      int sum = cv::sum(around_gomette).val[0];

      int sumTheorique = 4*atan(1)*pow((maxX-minX)/2,2)*255;
      if(abs(sum-sumTheorique)/sumTheorique>1.2 )
      {
        res=false;
      }
      std::cout << sum << std::endl;
      std::cout << sumTheorique << std::endl;
    }

  }


  return res;
}





cv::Mat QImageToCvMat(QImage const& img)
{
  return cv::Mat(img.height(), img.width(), CV_8UC4, const_cast<uchar*>(img.bits()), img.bytesPerLine()).clone();
}
