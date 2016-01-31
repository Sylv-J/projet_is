#ifndef MYQGRAPHICSLINEITEM_H
#define MYQGRAPHICSLINEITEM_H

#include "displayer.h"

class Displayer;

class MyQGraphicsLineItem : public QGraphicsLineItem

{

public:

    MyQGraphicsLineItem(const QLineF &line, Displayer *disp);
    void setUpperLine(MyQGraphicsLineItem *line);
    void setLowerLine(MyQGraphicsLineItem *line);
    MyQGraphicsLineItem* getUpperLine() const;
    MyQGraphicsLineItem* getLowerLine() const;

protected:

    void hoverEnterEvent(QGraphicsSceneHoverEvent *event);
    void hoverLeaveEvent(QGraphicsSceneHoverEvent *event);
    void mousePressEvent(QGraphicsSceneMouseEvent *event);
    void mouseMoveEvent(QGraphicsSceneMouseEvent *event);
    void mouseReleaseEvent(QGraphicsSceneMouseEvent *event);

private:

    Displayer *displayer;
    MyQGraphicsLineItem *upperLine;
    MyQGraphicsLineItem *lowerLine;

};

#endif // MYQGRAPHICSLINEITEM_H
