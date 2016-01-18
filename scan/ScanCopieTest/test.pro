QT += widgets testlib

SOURCES = testqstring.cpp \
    mywindow.cpp

# install
target.path = $$[QT_INSTALL_EXAMPLES]/qtestlib/tutorial1
INSTALLS += target

HEADERS += \
    mywindow.h
