#include "mywindow.h"

#define WINDOW_HEIGHT 1000
#define WINDOW_WIDTH 1800
#define BUTTON_MAX_HEIGHT 50
#define BUTTON_MAX_WIDTH 150
#define FORMAT "PNG"

MyWindow::MyWindow() : QWidget()

{

    init();

}


void MyWindow::init()

{

    this->setFixedSize(WINDOW_WIDTH, WINDOW_HEIGHT);

    displayLabel = new QLabel(this);
    displayLabel->setAlignment(Qt::AlignCenter);
    displayLabel->setTextFormat(Qt::PlainText);
    displayLabel->setText("(vide)");
    displayLabel->setMaximumWidth(WINDOW_WIDTH/2);

    LscrollArea = new QScrollArea;
    LscrollArea->setBackgroundRole(QPalette::Dark);

    LscrollArea->setHorizontalScrollBarPolicy(Qt::ScrollBarAlwaysOff);

    m_buttonClose = new QPushButton("Fermer");
    m_buttonChooseImages = new QPushButton("Choisir images");
    m_buttonAssemble = new QPushButton("Assembler");
    m_buttonSave = new QPushButton("Enregistrer sous...");

    m_buttonClose->setMaximumSize(BUTTON_MAX_WIDTH, BUTTON_MAX_HEIGHT);
    m_buttonChooseImages->setMaximumSize(BUTTON_MAX_WIDTH, BUTTON_MAX_HEIGHT);
    m_buttonAssemble->setMaximumSize(BUTTON_MAX_WIDTH, BUTTON_MAX_HEIGHT);
    m_buttonSave->setMaximumSize(BUTTON_MAX_WIDTH, BUTTON_MAX_HEIGHT);

    windowLayout = new QGridLayout;

    windowLayout->addWidget(displayLabel, 0, 2, 5, 1);
    windowLayout->addWidget(m_buttonChooseImages, 0, 0);
    windowLayout->addWidget(m_buttonAssemble, 1, 0);
    windowLayout->addWidget(m_buttonSave, 2, 0);
    windowLayout->addWidget(m_buttonClose, 4, 0);

    windowLayout->setRowMinimumHeight(3, this->size().height() - 4*BUTTON_MAX_HEIGHT - 20);
    windowLayout->setColumnMinimumWidth(1, (this->size().width() - BUTTON_MAX_WIDTH - displayLabel->width())/2);
    windowLayout->setColumnMinimumWidth(3, (this->size().width() - BUTTON_MAX_WIDTH - displayLabel->width())/2);

    this->setLayout(windowLayout);

    rowNumbers = 0;
    colNumbers = 0;

    QObject::connect(m_buttonClose, SIGNAL(clicked()), this, SLOT(close()));
    QObject::connect(m_buttonChooseImages, SIGNAL(clicked()), this, SLOT(chooseImages()));
    QObject::connect(m_buttonAssemble, SIGNAL(clicked()), this, SLOT(assemble()));
    QObject::connect(m_buttonSave, SIGNAL(clicked()), this, SLOT(saveFinalImage()));

}


void MyWindow::openDialog(QWidget *parent, const QString &title, const QString &message)

{

    if(title == "Avertissement")
    {
        QMessageBox::warning(parent, title, message);
    }

    else if(title == "Erreur")
    {
        QMessageBox::critical(parent, title, message);
    }

    else
    {
        QMessageBox::information(parent, title, message);
    }

}


void MyWindow::chooseImages()

{

    filenames = QFileDialog::getOpenFileNames(this, "Choix des images", QString(), "Images (*.png)");

    if(filenames.isEmpty())
    {
        openDialog(this, "Information", "Opération annulée : vous n'avez choisi aucun fichier.");
        return;
    }

    images.clear();
    colNumbers = 0;
    rowNumbers = 0;

    QSize currentImageSize = QSize();
    for(int i = 0; i < filenames.size(); ++i)
    {
        QImage currentImage(filenames.at(i), FORMAT);

        if(currentImage.isNull())
        {
            openDialog(this, "Erreur", "Le chargement d'une ou plusieurs image(s) a échoué.");
            return;
        }

        images.push_back(currentImage);

        currentImageSize = images[i].size();
        colNumbers = fmax(colNumbers, currentImageSize.width());
        rowNumbers += currentImageSize.height();
    }

}


void MyWindow::assemble()

{

    if(images.size() == 0)
    {
        openDialog(this, "Avertissement", "Vous n'avez pas encore choisi les images à assembler.");
        return;
    }

    finalImage = QImage(colNumbers, rowNumbers, images[0].format());
    finalImage.fill(QColor(255, 255, 255));

    QPainter painter(&finalImage);
    int currentRow = 0;
    for(int i = 0; i < images.size(); i++)
    {
        QPoint drawingPoint(0, currentRow);
        painter.drawImage(drawingPoint, images[i]);

        currentRow += images[i].size().height();
    }

    painter.end();

    windowLayout->removeWidget(displayLabel);

    displayLabel = new QLabel(this);
    displayLabel->setAlignment(Qt::AlignCenter);
    displayLabel->setPixmap(QPixmap::fromImage(finalImage));

    LscrollArea->setWidget(displayLabel);

    windowLayout->addWidget(LscrollArea, 0, 2, 5, 1);

    windowLayout->setColumnMinimumWidth(1, (this->size().width() - BUTTON_MAX_WIDTH - displayLabel->width())/2);
    windowLayout->setColumnMinimumWidth(3, (this->size().width() - BUTTON_MAX_WIDTH - displayLabel->width())/2);

    openDialog(this, "Information", "Les images ont été assemblées !");

}


void MyWindow::saveFinalImage()

{
    if(finalImage.isNull())
    {
        openDialog(this, "Avertissement", "Vous n'avez pas encore construit l'image finale.");
        return;
    }

    QString filePath = QFileDialog::getSaveFileName(this,tr("Save File"),
                                                    "/home/jana/untitled.png",
                                                    tr("Images (*.png)"));

    bool success = finalImage.save(filePath, "PNG", -1);

    if(!success)
    {
        openDialog(this, "Erreur", "L'enregistrement de l'image a échoué.");
    }
}

