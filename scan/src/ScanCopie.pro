QT += widgets

CONFIG += static

QMAKE_LIBS += `pkg-config opencv --cflags pkg-config opencv --libs`

SOURCES += \
    main.cpp \
    mywindow.cpp \
    displayer.cpp \
    myqgraphicslineitem.cpp \
    myqgraphicsscene.cpp \
    traitement.cpp

HEADERS += \
    mywindow.h \
    displayer.h \
    myqgraphicslineitem.h \
    myqgraphicsscene.h \
    traitement.h
