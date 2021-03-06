package markdown

import (
	"testing"
)

func TestTitle(t *testing.T) {
	for i := -10; i <= 10; i++ {
		t.Logf("%s", Title(i, "hello world"))
	}
}

func TestLink(t *testing.T) {
	link := Link("hello world", "https://example.com")
	if link.String() != "[hello world](https://example.com)" {
		t.Fatal("incorrect link")
	}
}

func TestBold(t *testing.T) {
	bold := Bold(Inline("hello world"))
	if bold.String() != "**hello world**" {
		t.Fatal("incorrect bold")
	}
}

func TestCode(t *testing.T) {
	code := Code("package main")
	if code.String() != "`package main`" {
		t.Fatal("incorrect code")
	}
}

func TestQuote(t *testing.T) {
	quote := Quote("hello\nworld\nend")
	if quote.String() != "> hello\n> world\n> end" {
		t.Fatal("incorrect quote")
	}
}

func TestColorGreen(t *testing.T) {
	green := ColorGreen(Bold(Inline("hello")))
	if green.String() != `<font color="info">**hello**</font>` {
		t.Fatal("incorrect green")
	}
}

func TestColorGay(t *testing.T) {
	gray := ColorGray(Bold(Inline("hello")))
	if gray.String() != `<font color="comment">**hello**</font>` {
		t.Fatal("incorrect green")
	}
}

func TestColorRed(t *testing.T) {
	red := ColorRed(Bold(Inline("hello")))
	if red.String() != `<font color="warning">**hello**</font>` {
		t.Fatal("incorrect green")
	}
}
