#include <QApplication>
#include "mywindow.h"



int main(int argc, char *argv[])

{

    QApplication app(argc, argv);

    MyWindow window;
    window.show();

    return app.exec();

}
