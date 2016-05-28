#ifndef _FIND_H_
#define _FIND_H_

#include <QDialog>

class QCheckBox;
class QLabel;
class QLineEdit;
class QPushButton;
class QWidget;

class FindDialog : public QDialog {
Q_OBJECT
    public:
        FindDialog(QWidget *parent = 0);
    signals:
        void findNext(const QString &str, Qt::CaseSensitivity cs);
        void findPrev(const QString &str, Qt::CaseSensitivity cs);
    private slots:
        void findClicked();
        void enableFindButton(const QString &text);
    private:
        QLabel *label;
        QLineEdit *lineEdit;
        QCheckBox *caseCheckBox;
        QCheckBox *backwardCheckBox;
        QPushButton *findButton;
        QPushButton *closeButton;
};

#endif // FIND

