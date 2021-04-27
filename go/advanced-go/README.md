Go的一些小知识点
-------------


* 可以在`build`命令中增加`-x`参数打印详细的编译日志。
* cgo环境下可以在`build`命令中增加`-work`参数将中间文件保留下来，或者使用`go tool cgo main.go`进行编译。
* 在`main`包里使用asm，必须使用`go build`，不能指定源文件（与cgo编译类似）
* 在使用`DATA`初始化内存时，初始化数据宽度必须是`1, 2, 4, 8`之一，因为再大的内存无法一次性用一个uint64来表示。
* X86处理器是小端字节序的。