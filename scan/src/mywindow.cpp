#include "mywindow.h"

#define FORMAT "PNG"

MyWindow::MyWindow() : QWidget()

{

    init();

}


void MyWindow::init()

{

    this->showMaximized();

    displayer = new Displayer(this, "display zone");

    m_buttonClose = new QPushButton("&Fermer");
    m_buttonChooseImages = new QPushButton("&Choix des images");
    m_buttonSave = new QPushButton("&Enregistrer");

    windowLayout = new QHBoxLayout;
    buttonLayout = new QVBoxLayout;

    buttonLayout->addWidget(m_buttonChooseImages);
    buttonLayout->addWidget(m_buttonSave);
    buttonLayout->addStretch();
    buttonLayout->addWidget(m_buttonClose);

    windowLayout->addLayout(buttonLayout);
    windowLayout->addSpacing(40);
    windowLayout->addWidget(displayer);

    this->setLayout(windowLayout);

    rowNumbers = 0;
    colNumbers = 0;

    QObject::connect(m_buttonClose, SIGNAL(clicked()), this, SLOT(close()));
    QObject::connect(m_buttonChooseImages, SIGNAL(clicked()), this, SLOT(chooseImages()));
    QObject::connect(m_buttonSave, SIGNAL(clicked()), this, SLOT(saveImages()));

    hideAllButtons();
    m_buttonChooseImages->show();

}


void MyWindow::chooseImages()

{

    filenames = QFileDialog::getOpenFileNames(this, "Choix des images", QString(), "Images (*.gif *.png *.jpg *.jpeg *.tif *.pct *.bmp)");

    if(filenames.isEmpty())
    {
        return;
    }

    displayer->clean();

    for(int i = 0; i < chosenImages.size(); i++)
    {
        delete chosenImages[i];
    }

    chosenImages.clear();
    splitPoints.clear();
    colNumbers = 0;
    rowNumbers = 0;

    QSize currentImageSize = QSize();
    for(int i = 0; i < filenames.size(); ++i)
    {
        QImage currentImage(filenames.at(i));

        if(currentImage.isNull())
        {
            QMessageBox::critical(this, "Erreur", "Le chargement d'une ou plusieurs image(s) a échoué.");
            return;
        }

        QImage *newImage = new QImage(currentImage);
        chosenImages.push_back(newImage);

        detectCircles(newImage, &splitPoints);

        currentImageSize = chosenImages[i]->size();
        colNumbers = fmax(colNumbers, currentImageSize.width());
        rowNumbers += currentImageSize.height();

    }

    displayer->addImages(chosenImages);

    displayer->drawDivLines(splitPoints);

    hideAllButtons();
    m_buttonChooseImages->setText("Nouveau &choix");
    m_buttonChooseImages->show();
    m_buttonSave->show();

}


void MyWindow::split()

{

    for(int i = 0; i < splittedImages.size(); i++)
    {
        delete splittedImages[i];
    }
    splittedImages.clear();

    QVector<Couple*> splitPoints;
    displayer->getSplitPoints(&splitPoints);

    Couple currentPos(0, 0);
    for(int i = 0; i < splitPoints.size(); i++)
    {
        qreal height = -currentPos.getRow();
        for(int j = currentPos.getImageNumber(); j < splitPoints[i]->getImageNumber(); j++)
        {
            height += chosenImages[j]->height();
        }
        height += splitPoints[i]->getRow();

        QImage *im = new QImage(colNumbers, (int)height, chosenImages[0]->format());
        im->fill(QColor(255, 255, 255));

        QPainter painter(im);
        QPointF drawingPoint(0, 0);
        for(int j = currentPos.getImageNumber(); j < splitPoints[i]->getImageNumber(); j++)
        {
            painter.drawImage(drawingPoint, chosenImages[j]->copy(0, currentPos.getRow(), colNumbers, chosenImages[j]->height()));
            drawingPoint += QPointF(0, chosenImages[j]->height() - currentPos.getRow());
            currentPos.setRow(0);
            currentPos.setImageNumber(j+1);
        }

        painter.drawImage(drawingPoint, chosenImages[currentPos.getImageNumber()]->copy(0, currentPos.getRow(), colNumbers, splitPoints[i]->getRow()));
        currentPos.setRow(splitPoints[i]->getRow());

        splittedImages.push_back(im);
    }

}


void MyWindow::saveImages()

{

    this->split();

    QDir saveDir = displayer->getSaveDir();

    bool pathCreation = saveDir.mkpath(saveDir.path());

    if(!pathCreation)
    {
        QMessageBox::critical(this, "Erreur", "La création du dossier d'enregistrement des images a échoué.");
    }

    QString filePath = QDir::toNativeSeparators(QString("%1%2%3.png").arg(saveDir.path()).arg("/image_"));

    int fail = 0;

    for(int i = 0; i < splittedImages.size(); i++)
    {
        bool save = splittedImages[i]->save(filePath.arg(i+1, 2, 10, QChar('0')), FORMAT);
        if(!save)
        {
            fail++;
        }
    }

    if(fail > 0)
    {
        QMessageBox::critical(this, "Erreur", QString("Echec de l'enregistrement de %1 image(s).").arg(fail));
    }

    displayer->clean(fail == 0);

    hideAllButtons();
    m_buttonChooseImages->setText("&Choix des images");
    m_buttonChooseImages->show();

}

void MyWindow::hideAllButtons()

{

    m_buttonChooseImages->hide();
    m_buttonSave->hide();

}

