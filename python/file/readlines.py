#!/usr/bin/python35

fp = open('hello.txt')

print(fp.readlines())

fp.seek(0, 0)
print(fp.readlines(4))

fp.close()

print('*' * 33)

with open('hello.txt', 'r') as fp:
    for line in fp:
        print(line)
