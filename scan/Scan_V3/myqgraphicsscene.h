#ifndef MYQGRAPHICSSCENE_H
#define MYQGRAPHICSSCENE_H

#include "displayer.h"

class Displayer;

class MyQGraphicsScene : public QGraphicsScene

{

public:

    MyQGraphicsScene(Displayer *disp);

protected:

    void mouseDoubleClickEvent(QGraphicsSceneMouseEvent *event);

private:

    Displayer *displayer;

};

#endif // MYQGRAPHICSSCENE_H
