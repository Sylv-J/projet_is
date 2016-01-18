#ifndef TRAITEMENT
#define TRAITEMENT


#include <iostream>
#include <vector>

#include <QApplication>
#include <QImage>
#include <QtGui>
#include <QLayout>

#include <opencv2/core/core.hpp>
#include <opencv2/opencv.hpp>
#include <opencv2/highgui/highgui.hpp>
#include <opencv2/imgproc/imgproc.hpp>




void detectCircle(QImage *qim);


cv::Mat QImageToCvMat(QImage const& src);


#endif
