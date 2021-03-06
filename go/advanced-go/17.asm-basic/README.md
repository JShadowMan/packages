Go汇编 - 基础知识
---------------


### 计算机架构

当前流行的计算机基本采用的是冯·诺依曼计算机体系结构（还有哈佛体系结构）。冯·诺依曼结构也被称为普林斯顿结构，采用的是一种将程序指令和数据储存在一起的存储结构。

#### X86-64体系结构

X86-64是AMD公司设计的X86架构的64位扩展，向后兼容16位及32位的X86架构。X86-64目前正式名称为AMD64。
```text
+---------+---------+
| SEGMENT | ADDRESS |
+---------+---------+
| STACK   |   HIGH  |   用于管理每个函数调用时相关的数据
| ...     |    ↓    |
| UNUSED  |    .    |
| ...     |    .    |   
| HEAP    |    .    |   用于管理动态的数据
| DATA    |    .    |   存放全局数据
| RODATA  |    ↑    |   对应只读的数据段
| TEXT    |   LOW   |   一般对应代码段，用于存储要执行的指令数据
+---------+---------+
```

#### Go汇编中的伪寄存器

Go汇编为了简化汇编代码的编写，引入了`PC, FP, SP, SB`这4个伪寄存器。
 * `PC(Program counter)`：伪寄存器`PC`其实就是`IP`指令寄存器的别名
 * `FP(Frame pointer)`：表示函数的帧指针，一般用来返回函数的参数和返回值
 * `SP(Stack pointer)`：表示当前函数栈帧的底部（不包括参数和返回值部分），一般用于定位局部变量
    * 真`SP`寄存器：对应的是栈的底部，一般用于定位调用其他函数的参数和返回值（`(SP)`，`+8(SP)`）
    * 伪`SP`寄存器：一般需要一个标识符和偏移量作为前置（`·Name(SP)`，`·Name+9(SP)`）
 * `SB(Static base pointer)`：表示全局符号位置

#### X86-64指令集

汇编语言在不同的CPU类型、不同的操作系统、不同的汇编工具链下是不可移植的。这种不可移植性正是其普及的极大障碍。

`MOV`指令可以用于将字面值转义到寄存器、将字面值转义到内存、寄存器之间的数据传输、寄存器与内存之间的数据传输（`MOV`指令的内存操作数只能有一个，可以通过临时寄存器达到类似目的）。

`MOV`指令有不同的宽度，分别为`MOVB (byte=1)`，`MOVW (word=2)`，`MOVL (long=4)`，`MOVQ (quad=4)`

基础的算术指令有`ADD`，`SUB`，`MUL`，`DIV`最终结果将存入目标寄存器。基础的逻辑运算指令有`AND`，`OR`，`NOT`等几个指令。控制指令有`CMP`，`JMP-if-x`，`JMP`，`CALL`，`RET`等指令（无条件和有条件的跳转是实现分支和循环控制流的基础指令）。

`LEA`指令用于取地址，并将其存入寄存器。`PUSH`和`POP`用于入栈和出栈指令。

__Go汇编语言可能并没有支持全部的CPU指令，如果遇到没有支持的CPU指令，可以通过Go汇编提供的`BYTE`命令将真实的CPU指令对应的机器码填充到对应的位置。__


### 常量和全局变量

程序中一切变量的初始值都直接或间接地依赖常量或常量表达式生成。有了变量滞后，就可以衍生定义全局变量，并使用常量组成的表达式初始化其他变量。

#### 常量

Go汇编中常量以美元符号`$`为前缀。常量的类型有整数常量、浮点数常量、字符常量和字符串常量。
```text
$1      // ord
$0x12   // hex
$2.33   // float
$'c'    // char
$"str"  // string
```
其中整数默认是十进制格式，也可以用十六进制表示。**所有的常量最终都必须和要初始化的变量内存大小匹配**（不匹配是会截断？）。

Go汇编中的常量不仅只有编译时常量，还包含运行时常量（包中的全局变量和全局函数）。因为全局变量和全局函数的地址在运行时是固定不变的，所以地址不变的变量和函数的地址也是一种汇编常量（以`$`加上符号可以取到符号的地址）。

#### 全局变量

变量根据作用域和生命周期有全局变量和局部变量之分。全局变量时包级变量，一般有固定的内存地址，生命周期跨越整个程序的运行。局部变量一般是函数内定义的变量，只在函数被执行时创建，函数返回时回收（不考虑闭包）。

Go汇编角度上看，全局变量（全局函数）都是通过一个符号来引用对应的内存（区别只是内存中是数据还是指令）。我们甚至可以像操作数据那样动态生成指令（JIT技术的原理）。而局部变量则只能通过SP栈空间来隐式定义和访问。

>
> 在Go汇编语言中，内存通过伪寄存器`SB`来定位，`SB`是`Static Base Pointer`的缩写，
> 表示为静态内存的开始地址。
>
> 可以将`SB`当做一个与内存大小相同的字节数组，所有的静态全局符号都是通过`SB`加上一个偏移量定位。
>
> 我们定义的符号其实就是相对于`SB`开始地址的偏移量。
>

符号以`·`开头的表示为当前包的变量，符号对应的内存宽度必须是2的整数倍，编译器会保证变量的真实地址对齐到机器字的倍数上。**在Go汇编中定义全局变量，我们只关心变量的符号和内存大小，具体的类型只能在Go语言中声明**。

##### 数组类型变量

Go语言中的数组是一种扁平内存结构的基础类型。
```text
// var arrayValue [4]uint32
GLOBL ·arrayValue(SB), $32
DATA ·arrayValue+0(SB)/4, $0 // arrayValue[0] = 0
DATA ·arrayValue+4(SB)/4, $1 // arrayValue[1] = 1
DATA ·arrayValue+8(SB)/4, $2 // arrayValue[2] = 2
DATA ·arrayValue+12(SB)/4, $3 // arrayValue[3] = 3
```

##### 布尔类型变量

布尔类型的内存大小为1，并且汇编中定义的变量需要手工指定初始值，否则可能导致产生未初始化的变量。
**当需要将1个直接的布尔变量加载到8个直接的寄存器时，需要使用`MOVBQZX`指令将不足的高位补0**。
```text
GLOBL ·boolValue(SB), $1
DATA ·boolValue(SB)/1, $1 // true(not zero)
```

##### 整数类型变量

汇编定义变量初始化时并不区分整数是否有符号，只有在CPU指令处理改寄存器数时，才会根据指令的类型区分是否带符号位。
```text
GLOBL ·intValue(SB), $8
DATA ·intValue(SB)/8, $0xffffffffffffffff
```

##### 浮点类型变量

Go语言的浮点数遵循`IEEE 754`标准，有单精度和双精度之分。

**精简的算术指令都是针对整数的，如果需要通过整数指令处理浮点数的算术必须要根据浮点数的运算规则进行：先对齐小数点，然后进行整数加减法，最后再对结果进行归一化并处理精度舍入问题。**
```text
GLOBL ·floatValue(SB), $8
DATA ·floatValue(SB)/8, $2.33
```

##### 字符串类型

从汇编角度看，字符串只是一个结构体变量（`reflect.StringHeader`）。我们可以使用`symbal<>`的方式定义一个当前文件内的私有变量。
```text
GLOBL ·stringValue(SB), $16
DATA ·stringValue(SB)/8, $·text<>(SB)
DATA ·stringValue+8(SB)/8, $10

GLOBL ·text<>(SB), NOPTR, $16
DATA ·text<>(SB)/8, $"hello world long long" // overflow
```

##### 切片类型

切片类型变量与字符串类型相似，这不过对应的切片头结构会比字符串头结构多一个字段（`cap`）
```text
GLOBL ·sliceValue(SB), $16
DATA ·sliceValue(SB)/8, $·value<>(SB)
DATA ·sliceValue+8(SB)/8, $10
DATA ·sliceValue+8(SB)/8, $16

GLOBL ·value<>(SB), NOPTR, $16
DATA ·value<>(SB)/8, $"hello"
```

##### map/channel类型

`map`和`channel`类型没有公开的内部结构，它们只是一种未知数据结构的指针，无法直接初始化。但是可以使用`runtime.makemap`和`runtime.makechanel`来创建。


##### 变量的内存布局

在Go汇编中变量是没有类型的（类型在Go中定义），所以在Go中不同类型的变量底层可能对应的是相同的内存结构。深刻理解每个变量的内存布局是汇编编程的必备条件。

**例如，一个数组和一个结构体变量都是在data段分配内存，元素的地址依次从低到高排列。**

##### 标识符规则和特殊标志

Go语言中的标识符可以由绝对的包路径加标识符本身定位，在Go汇编中是通过特殊的符号来表示斜杠和点符号，这样简化汇编器词法扫描部分的实现，只需要进行字符串替换就行。

Go汇编中可以通过在符号后加`<>`的方式定义私有标识符（类似于C语言中的`static`变量）。

此外，Go汇编还在`textflag.h`中定义了一些标志，其中变量的标志有：
 * `DUPOK(duplicate ok)`：表示该变量对应的标识符可能有多个，在链接时只选择其中一个即可（一般用于合并相同的常量字符串，减少重复数据占用的空间）
 * `RODATA(read only data)`：表示将变量定义在只读内存段，后续任何对该变量的修改操作将导致异常（`recover`也无法捕获）
 * `NOPTR(no pointer)`：表示此变量内部不含指针数据，让垃圾回收器忽略对该变量的扫描
