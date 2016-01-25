#include "mywindow.h"

#define BUTTON_MAX_HEIGHT 50
#define BUTTON_MAX_WIDTH 150
#define FORMAT "PNG"

MyWindow::MyWindow() : QWidget()

{

    init();

}


void MyWindow::init()

{

    displayer = new Displayer(this, "display zone");

    m_buttonClose = new QPushButton("&Fermer");
    m_buttonChooseImages = new QPushButton("&Choisir images");
    m_buttonAssemble = new QPushButton("&Assembler");
    m_buttonSplit = new QPushButton("&Diviser");
    m_buttonSave = new QPushButton("&Enregistrer sous...");

    windowLayout = new QHBoxLayout;
    buttonLayout = new QVBoxLayout;

    buttonLayout->addWidget(m_buttonChooseImages);
    buttonLayout->addWidget(m_buttonAssemble);
    buttonLayout->addWidget(m_buttonSplit);
    buttonLayout->addWidget(m_buttonSave);
    buttonLayout->addStretch();
    buttonLayout->addWidget(m_buttonClose);

    windowLayout->addLayout(buttonLayout);
    windowLayout->addSpacing(40);
    windowLayout->addWidget(displayer->getScrollArea());

    this->setLayout(windowLayout);

    rowNumbers = 0;
    colNumbers = 0;

    QObject::connect(m_buttonClose, SIGNAL(clicked()), this, SLOT(close()));
    QObject::connect(m_buttonChooseImages, SIGNAL(clicked()), this, SLOT(chooseImages()));
    QObject::connect(m_buttonAssemble, SIGNAL(clicked()), this, SLOT(assemble()));
    QObject::connect(m_buttonSplit, SIGNAL(clicked()), this, SLOT(split()));
    QObject::connect(m_buttonSave, SIGNAL(clicked()), this, SLOT(saveImages()));

    hideAllButtons();
    m_buttonChooseImages->show();

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

    filenames = QFileDialog::getOpenFileNames(this, "Choix des images", QString(), "Images (*.png *.jpg *.bmp *.tif *.gif *.pct *.jpeg)");

    if(filenames.isEmpty())
    {
        openDialog(this, "Information", "Opération annulée : vous n'avez choisi aucun fichier.");
        return;
    }

    for(int i = 0; i < chosenImages.size(); i++)
    {
        delete chosenImages[i];
    }
    chosenImages.clear();
    colNumbers = 0;
    rowNumbers = 0;

    QSize currentImageSize = QSize();
    for(int i = 0; i < filenames.size(); ++i)
    {
        QImage currentImage(filenames.at(i));

        if(currentImage.isNull())
        {
            openDialog(this, "Erreur", "Le chargement d'une ou plusieurs image(s) a échoué.");
            return;
        }

        chosenImages.push_back(new QImage(currentImage));

        currentImageSize = chosenImages[i]->size();
        colNumbers = fmax(colNumbers, currentImageSize.width());
        rowNumbers += currentImageSize.height();
    }

    displayer->clean();
    displayer->addImages(chosenImages);


    hideAllButtons();
    m_buttonAssemble->show();

}


void MyWindow::assemble()

{

    fusionnedImage = QImage(colNumbers, rowNumbers, chosenImages[0]->format());
    fusionnedImage.fill(QColor(255, 255, 255));

    QPainter painter(&fusionnedImage);
    int currentRow = 0;
    for(int i = 0; i < chosenImages.size(); i++)
    {
        QPoint drawingPoint(0, currentRow);
        painter.drawImage(drawingPoint, *chosenImages[i]);

        currentRow += chosenImages[i]->size().height();
    }

    painter.end();

    // TODO : mettre a jour divisionPoints
    divisionPoints.push_back(0);
    detectCircles(&fusionnedImage, &divisionPoints);
    divisionPoints.push_back(rowNumbers);

    for(int i = 0; i < divisionLines.size(); i++)
    {
        delete divisionLines[i];
    }
    divisionLines.clear();

    for(int i = 0; i < divisionPoints.size() - 2; i++)
    {
        divisionLines.push_back(new QLineF(QPointF(0, divisionPoints[i+1]), QPointF(colNumbers, divisionPoints[i+1])));
    }


    displayer->clean();
    displayer->addImage(&fusionnedImage);
    displayer->drawDivLines(divisionLines);

    hideAllButtons();
    m_buttonSplit->show();

    openDialog(this, "Information", "Les images ont été assemblées !");

}


void MyWindow::split()

{

    for(int i = 0; i < splittedImages.size(); i++)
    {
        delete splittedImages[i];
    }
    splittedImages.clear();

    for(int i = 0; i < divisionPoints.size() - 1; i++)
    {
        QImage *im = new QImage(colNumbers, divisionPoints[i+1] - divisionPoints[i], fusionnedImage.format());
        im->fill(QColor(255, 255, 255));

        QPainter painter(im);
        painter.drawImage(QPoint(0, 0), fusionnedImage.copy(0, divisionPoints[i], colNumbers, divisionPoints[i+1] - divisionPoints[i]));

        splittedImages.push_back(im);
    }

    displayer->clean();
    displayer->addImages(splittedImages);

    hideAllButtons();
    m_buttonSave->show();

}


void MyWindow::saveImages()

{

    QString folder = QFileDialog::getExistingDirectory(this, tr("Choix du dossier d'enregistrement"));

    if(folder.isEmpty()) return;

    bool ok;
    QString name = QInputDialog::getText(this, tr("Choisir le modèle de nom de sauvegarde"), tr("Veuillez choisir le nom type qui sera utilisé pour l'enregistrement des images.\n Exemple : mettre le nom \"image\" enregistrera les images sous les noms \"image01.png\", \"image02.png\" etc..."), QLineEdit::Normal, "untitled", &ok);

    if(!ok) return;

    QString filePath = QDir::toNativeSeparators(QString("%1%2%3%4.png").arg(folder).arg("/").arg(name));

    for(int i = 0; i < splittedImages.size(); i++)
    {
        splittedImages[i]->save(filePath.arg(i+1, 2, 10, QChar('0')), FORMAT);
    }

    hideAllButtons();

}

void MyWindow::hideAllButtons()

{

    m_buttonChooseImages->hide();
    m_buttonAssemble->hide();
    m_buttonSplit->hide();
    m_buttonSave->hide();

}
