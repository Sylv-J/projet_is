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
#include "Traitement.h"


class MyWindow : public QWidget

{

    Q_OBJECT

    public:

    MyWindow();
    void init();
    void openDialog(QWidget *parent, const QString &title, const QString &message);

    public slots:

    void chooseImages();
    void assemble();
    void split();
    void saveImages();

    private:

    QHBoxLayout *windowLayout;
    QVBoxLayout *buttonLayout;

    QPushButton *m_buttonClose;
    QPushButton *m_buttonChooseImages;
    QPushButton *m_buttonAssemble;
    QPushButton *m_buttonSplit;
    QPushButton *m_buttonSave;

    QStringList filenames;

    QVector <QImage*> chosenImages;

    QImage fusionnedImage;
    int rowNumbers;
    int colNumbers;

    QVector <QLineF*> divisionLines;
    QVector <int> divisionPoints;
    QVector <QImage*> splittedImages;

    Displayer *displayer;

    void hideAllButtons();

};

#endif // MYWINDOW_H
