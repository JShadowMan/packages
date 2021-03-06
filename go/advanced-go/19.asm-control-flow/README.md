Go汇编 - 控制流
-------------

程序主要由顺序、分支和循环几种执行流程。


### 顺序执行

所有不含分支、循环和`goto`语句并且没有递归调用的Go函数一般都是顺序执行的（考虑指令重排？）。


### if/goto跳转

在Go中对于`goto`语句的原则是：如果可以不使用`goto`语句，那么就不要使用`goto`语句。而且Go中的`goto`语句有严格的限制：无法跨越代码块，并且在被跨域的代码中不能包含有变量定义的语句。

但是`goto`在Go汇编中却是构建整个汇编代码控制流的基石。`goto`近似等价于汇编语言中的无条件跳转指令`JMP`，配合`if`条件`goto`就组成了有条件跳转指令。


### for循环

经典`for`循环由初始化、约束条件、迭代步长组成，配合循环内部的`if`语句，这种`for`结构可以模拟其他各种循环结构。
