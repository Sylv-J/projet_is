#include "displayer.h"

Displayer::Displayer(QWidget *parent, QString name) : QWidget(parent)

{

    this->setObjectName(name);

    layout = new QVBoxLayout;

    scrollArea = new QScrollArea;
    scrollArea->setBackgroundRole(QPalette::Dark);
    scrollArea->setWidgetResizable(true);
    scrollArea->setWidget(this);

    this->setLayout(layout);

    emptyLabel = new QLabel("VIDE", this);
    emptyLabel->setAlignment(Qt::AlignCenter);
    emptyLabel->setTextFormat(Qt::PlainText);
    layout->addWidget(emptyLabel);

}


QScrollArea *Displayer::getScrollArea()

{
    return scrollArea;
}


void Displayer::addImage(QImage *image)

{

    emptyLabel->hide();

    int initialSize = labels.size();

    labels.push_back(new QLabel(this));
    labels[initialSize]->setAlignment(Qt::AlignCenter);
    labels[initialSize]->setPixmap(QPixmap::fromImage(*image));

    images.push_back(image);

    layout->addWidget(labels[initialSize]);

}


void Displayer::addImages(const QVector<QImage*> &im_vect)

{

    emptyLabel->hide();

    int initialSize = labels.size();

    for(int i = 0; i < im_vect.size(); i++)
    {
        labels.push_back(new QLabel(this));
        labels[initialSize + i]->setAlignment(Qt::AlignCenter);
        labels[initialSize + i]->setPixmap(QPixmap::fromImage(*im_vect[i]));

        images.push_back(im_vect[i]);

        layout->addWidget(labels[i]);
    }

}


void Displayer::drawDivLines(const QVector<QLineF *> &divisionLines)

{

    if(labels.size() != 1) return;

    QPixmap pixmap = QPixmap::fromImage(*images[0]);

    QPainter painter(&pixmap);

    for(int i = 0; i < divisionLines.size(); i++)
    {
        painter.drawLine(*divisionLines[i]);
    }

    painter.end();

    labels[0]->setPixmap(pixmap);

}

void Displayer::clean()

{

    for(int i = 0; i < labels.size(); i++)
    {
        layout->removeWidget(labels[i]);
        delete labels[i];
    }

    labels.clear();
    images.clear();

    emptyLabel->show();

}
