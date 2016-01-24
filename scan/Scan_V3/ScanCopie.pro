QT += widgets

QMAKE_LIBS += `pkg-config opencv --cflags pkg-config opencv --libs`

SOURCES += \
    main.cpp \
    mywindow.cpp \
    displayer.cpp \
    Traitement.cpp

HEADERS += \
    mywindow.h \
    displayer.h \
    Traitement.h
