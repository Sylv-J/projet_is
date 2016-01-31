#include "myqgraphicsscene.h"


MyQGraphicsScene::MyQGraphicsScene(Displayer *disp) : QGraphicsScene()

{

    displayer = disp;

}


void MyQGraphicsScene::mouseDoubleClickEvent(QGraphicsSceneMouseEvent *event)

{

    if(event->button() == Qt::LeftButton && !displayer->isEmpty())
    {
        displayer->drawLine(event->scenePos().ry());
    }

}
