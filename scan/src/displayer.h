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
#include <QGraphicsScene>
#include <QGraphicsView>
#include <QGraphicsItem>
#include <QScrollArea>
#include <QListWidget>
#include <QLineF>
#include <QVBoxLayout>
#include <QGraphicsSceneMouseEvent>

#include "myqgraphicslineitem.h"
#include "myqgraphicsscene.h"

class MyQGraphicsLineItem;
class MyQGraphicsScene;
class Couple;

class Couple

{

public:

    Couple(qreal r, int im_number);
    qreal getRow() const;
    int getImageNumber() const;
    void setRow(qreal r);
    void setImageNumber(int im_number);

private:

    qreal row;
    int imageNumber;

};

class Displayer : public QWidget

{

public :

    Displayer(QWidget *parent, QString name = QString());
    bool isEmpty();
    QVector<MyQGraphicsLineItem*> &getLines();
    QGraphicsView* getView();
    int getSceneHeight() const;
    void findSaveDir();
    QDir getSaveDir() const;
    void setLineNumber();
    void showLabelPos(bool show, qreal pos = 0);
    void addImages(const QVector<QImage *> &im_vect);
    void setSceneWidth(int width);
    void drawLine(int posY);
    void removeLine(MyQGraphicsLineItem *line);
    void drawDivLines(const QVector<int> &splitPoints);
    void getSplitPoints(QVector<Couple *> *splitPoints);
    void clean(bool saveSuccess = false);

private :

    QHBoxLayout *mainLayout;
    QVBoxLayout *leftLayout;
    QVBoxLayout *rightLayout;
    QLabel *informations;
    MyQGraphicsScene *scene;
    QGraphicsView *view;
    bool empty;
    QGraphicsTextItem *emptyItem;
    QVector <MyQGraphicsLineItem*> lines;
    QVector <QGraphicsPixmapItem*> items;
    int sceneHeight;
    int sceneWidth;
    QLabel *lineNumber;
    QLabel *selectedLine;
    QLabel *position;
    QLabel *save;
    QDir saveDir;

};


#endif // DISPLAYER_H
