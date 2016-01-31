#ifndef MYWINDOW_H
#define MYWINDOW_H

#include <cmath>

#include <QApplication>
#include <QWidget>
#include <QPushButton>
#include <QMessageBox>
#include <QFileDialog>
#include <QInputDialog>
#include <QImage>
#include <QVector>
#include <QLabel>
#include <QPainter>
#include <QScrollArea>
#include <QHBoxLayout>
#include <QVBoxLayout>

#include "displayer.h"
#include "traitement.h"


class MyWindow : public QWidget

{

    Q_OBJECT

    public:

    MyWindow();
    void init();

    public slots:

    void chooseImages();
    void split();
    void saveImages();

    private:

    QHBoxLayout *windowLayout;
    QVBoxLayout *buttonLayout;
    Displayer *displayer;
    QPushButton *m_buttonClose;
    QPushButton *m_buttonChooseImages;
    QPushButton *m_buttonSave;
    QStringList filenames;
    QVector <QImage*> chosenImages;
    QVector <int> splitPoints;
    QVector <QImage*> splittedImages;
    int rowNumbers;
    int colNumbers;

    void hideAllButtons();

};

#endif // MYWINDOW_H
