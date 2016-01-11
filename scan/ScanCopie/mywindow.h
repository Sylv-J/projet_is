#ifndef MYWINDOW_H
#define MYWINDOW_H

#include <cmath>

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
    void saveFinalImage();

    private:

    QGridLayout *windowLayout;

    QPushButton *m_buttonClose;
    QPushButton *m_buttonChooseImages;
    QPushButton *m_buttonAssemble;
    QPushButton *m_buttonSave;

    QLabel *displayLabel;
    QVBoxLayout *labelLayout;
    QScrollArea *LscrollArea;

    QStringList filenames;
    QVector <QImage> images;

    QImage finalImage;
    int rowNumbers;
    int colNumbers;

};

#endif // MYWINDOW_H
