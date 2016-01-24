#ifndef DISPLAYER_H
#define DISPLAYER_H

#include <QApplication>
#include <QWidget>
#include <QPushButton>
#include <QMessageBox>
#include <QFileDialog>
#include <QImage>
#include <QVector>
#include <QLabel>
#include <QPainter>
#include <QGridLayout>
#include <QScrollArea>
#include <QListWidget>
#include <QLineF>

class Displayer : public QWidget

{

    public :

    Displayer(QWidget *parent, QString name = QString());
    QScrollArea *getScrollArea();
    void addImage(QImage *image);
    void addImages(const QVector<QImage *> &im_vect);
    void drawDivLines(const QVector <QLineF*> &divisionLines);
    void clean();

    private :

    QVBoxLayout *layout;
    QScrollArea *scrollArea;

    QVector <QImage*> images;
    QVector <QLabel*> labels;

    QLabel *emptyLabel;

};

#endif // DISPLAYER_H
