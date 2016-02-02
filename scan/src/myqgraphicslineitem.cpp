#include "myqgraphicslineitem.h"


MyQGraphicsLineItem::MyQGraphicsLineItem(const QLineF &line, Displayer *disp) : QGraphicsLineItem(line)

{

    displayer = disp;
    upperLine = NULL;
    lowerLine = NULL;
    move = false;
    this->setPen(QPen(Qt::black, 2));
    this->setAcceptHoverEvents(true);

}


void MyQGraphicsLineItem::setUpperLine(MyQGraphicsLineItem *line) { upperLine = line; }

void MyQGraphicsLineItem::setLowerLine(MyQGraphicsLineItem *line) { lowerLine = line; }

MyQGraphicsLineItem* MyQGraphicsLineItem::getUpperLine() const { return upperLine; }

MyQGraphicsLineItem* MyQGraphicsLineItem::getLowerLine() const { return lowerLine; }


void MyQGraphicsLineItem::hoverEnterEvent(QGraphicsSceneHoverEvent *event)

{

    this->setPen(QPen(Qt::red, 3));

}


void MyQGraphicsLineItem::hoverLeaveEvent(QGraphicsSceneHoverEvent *event)

{

    this->setPen(QPen(Qt::black, 2));

}


void MyQGraphicsLineItem::mousePressEvent(QGraphicsSceneMouseEvent *event)

{

    if(event->button() == Qt::LeftButton)
    {
        this->setPen(QPen(Qt::green, 2));
        displayer->showLabelPos(true, this->scenePos().ry());
        move = true;
    }

}


void MyQGraphicsLineItem::mouseMoveEvent(QGraphicsSceneMouseEvent *event)

{

    if(!move)
    {
        this->ungrabMouse();
        return;
    }


    qreal lastPosY = event->lastScenePos().ry();
    qreal newPosY = event->scenePos().ry();

    bool stop = false;

    if((upperLine != NULL && newPosY < upperLine->pos().ry() + 10) || newPosY < 0)
    {
        stop = true;
    }

    if((lowerLine != NULL && newPosY > lowerLine->pos().ry() - 10) || newPosY > displayer->getSceneHeight())
    {
        stop = true;
    }

    if(!stop)
    {
        this->setPos(0, newPosY);
    }

    else
    {
        QCursor::setPos(event->lastScreenPos());
        this->setPos(0, lastPosY);
    }

    displayer->showLabelPos(true, this->scenePos().ry());

}


void MyQGraphicsLineItem::mouseReleaseEvent(QGraphicsSceneMouseEvent *event)

{

    move = false;
    displayer->showLabelPos(false);

    if(event->button() == Qt::LeftButton)
    {
        this->setPen(QPen(Qt::red, 3));
    }

    if(event->button() == Qt::RightButton)
    {
        displayer->removeLine(this);
    }

}
