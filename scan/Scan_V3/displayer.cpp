#include "displayer.h"


Couple::Couple(qreal r, int im_number)

{

    row = r;
    imageNumber = im_number;

}

qreal Couple::getRow() const { return row; }

int Couple::getImageNumber() const { return imageNumber; }

void Couple::setRow(qreal r) { row = r; }

void Couple::setImageNumber(int im_number) { imageNumber = im_number; }


Displayer::Displayer(QWidget *parent, QString name) : QWidget(parent)

{

    this->setObjectName(name);

    mainLayout = new QHBoxLayout;
    leftLayout = new QVBoxLayout;
    rightLayout = new QVBoxLayout;
    scene = new MyQGraphicsScene(this);
    view = new QGraphicsView;

    view->setScene(scene);

    this->setLayout(mainLayout);

    emptyItem = scene->addText("VIDE", QFont("Arial", 10, QFont::Bold));
    scene->setSceneRect(emptyItem->sceneBoundingRect());

    informations = new QLabel("Choisissez les images à traiter.");
    informations->setAlignment(Qt::AlignCenter);
    informations->setFont(QFont("Times", 15, 3));

    leftLayout->addWidget(informations);
    leftLayout->addWidget(view);

    mainLayout->addLayout(leftLayout);

    empty = true;
    sceneHeight = 0;
    sceneWidth = 0;

}


bool Displayer::isEmpty() { return this->empty; }

QVector<MyQGraphicsLineItem*> &Displayer::getLines() { return lines; }

QGraphicsView* Displayer::getView() { return view; }

int Displayer::getSceneHeight() const { return sceneHeight; }


void Displayer::addImages(const QVector<QImage*> &im_vect)

{

    if(empty)
    {
        scene->removeItem(emptyItem);
    }

    empty = false;

    for(int i = 0; i < im_vect.size(); i++)
    {

        QGraphicsPixmapItem *newItem = scene->addPixmap(QPixmap::fromImage(*im_vect[i]));

        newItem->moveBy(0, sceneHeight);

        items.push_back(newItem);

        sceneHeight += im_vect[i]->height();
        if(sceneWidth < im_vect[i]->width())
        {
            sceneWidth = im_vect[i]->width();
        }

    }

    scene->setSceneRect(0, 0, sceneWidth, sceneHeight);

    informations->setText("Appuyer sur enregistrer pour diviser l'image au niveau des lignes et sauvegarder le résultat.\nDouble cliquez pour ajouter une ligne.\nDéplacer une ligne avec le bouton gauche de la souris.\nSupprimer une ligne avec le bouton droit de la souris.");

}


void Displayer::setSceneWidth(int width)

{
    if(width > 0)
    {
        sceneWidth = width;
    }
    else
    {
        sceneWidth = 500;
    }

}


void Displayer::drawLine(int posY)

{

    MyQGraphicsLineItem *newLine = new MyQGraphicsLineItem(QLineF(0, 0, sceneWidth, 0), this);
    scene->addItem(newLine);
    newLine->setPos(0, posY);

    bool success = false;
    for(int i = 0; i < lines.size(); i++)
    {
        if(posY < lines[i]->scenePos().ry())
        {

            if(i > 0)
            {
                newLine->setUpperLine(lines[i-1]);
                lines[i-1]->setLowerLine(newLine);
            }
            newLine->setLowerLine(lines[i]);
            lines[i]->setUpperLine(newLine);
            lines.insert(i, newLine);
            success = true;
            break;

        }
    }

    if(!success)
    {

        if(!lines.isEmpty())
        {
            newLine->setUpperLine(lines.last());
            lines.last()->setLowerLine(newLine);
        }
        lines.push_back(newLine);

    }


}


void Displayer::removeLine(MyQGraphicsLineItem *line)

{

    MyQGraphicsLineItem *upperLine = line->getUpperLine();
    MyQGraphicsLineItem *lowerLine = line->getLowerLine();

    if(upperLine != NULL)
    {
        upperLine->setLowerLine(lowerLine);
    }

    if(lowerLine != NULL)
    {
        lowerLine->setUpperLine(upperLine);
    }

    scene->removeItem(line);
    lines.remove(lines.indexOf(line));

    delete line;

}


void Displayer::drawDivLines(const QVector<int> &splitPoints)

{

    if(empty) return;

    for(int i = 0; i < splitPoints.size(); i++)
    {

        this->drawLine(splitPoints[i]);

    }

}


void Displayer::getSplitPoints(QVector<Couple*> *splitPoints)

{

    int currentRow = 0;
    int indexLine = 0;

    for(int i = 0; i < items.size(); i++)
    {

        int itemHeight = items[i]->pixmap().height();

        while(indexLine < lines.size() && lines[indexLine]->scenePos().ry() < currentRow + itemHeight)
        {

            splitPoints->push_back(new Couple(lines[indexLine]->scenePos().ry() - currentRow, i));
            indexLine++;

        }

        currentRow += itemHeight;

    }

    splitPoints->push_back(new Couple(items.last()->pixmap().height(), items.size()-1));

}


void Displayer::clean(bool saveSuccess)

{

    if(empty) return;

    scene->clear();
    scene->setSceneRect(emptyItem->sceneBoundingRect());

    items.clear();
    lines.clear();

    scene->addItem(emptyItem);
    empty = true;

    sceneHeight = 0;
    sceneWidth = 0;

    if(saveSuccess)
    {
        informations->setText("Les images ont été enregistrées avec succès. Choisissez les nouvelles images à traiter.");
    }
    else
    {
        informations->setText("Choisissez les images à traiter.");
    }

}
