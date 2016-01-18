#include "testqstring.h"
#include "MyWindow.h"
#include <QtTest/QtTest>

class TestQString: public QObject
{
    Q_OBJECT
private slots:
    void testRowNumbersInit();
    void testColNumbersInit();
    void testRowColNumbersImage();
};

void TestQString::testRowNumbersInit()
{
    MyWindow mr;
    QCOMPARE(mr.getRowNumbers(), 0);
}

void TestQString::testColNumbersInit()
{
    MyWindow mr;
    QCOMPARE(mr.getColNumbers(), 0);
}

void TestQString::testRowColNumbersImage()
{
    MyWindow mr;
    mr.chooseImages();
    QCOMPARE(mr.getRowNumbers(), 768);
    QCOMPARE(mr.getColNumbers(), 1366);
}


QTEST_MAIN(TestQString)
#include "testqstring.moc"
