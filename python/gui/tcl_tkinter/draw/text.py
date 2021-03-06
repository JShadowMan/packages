#!/usr/bin/env python3
import tkinter as tk
import tkinter.ttk as ttk

class CanvasDraw(tk.Frame):

    def __init__(self, parent):
        super(CanvasDraw, self).__init__(parent)

        self.__init_window_ui()

    def __init_window_ui(self):
        self.master.title('Canvas Draw Text')
        self.pack(fill = tk.BOTH, expand = True)
        self.style = ttk.Style()

        self.style.configure("TFrame", background = '#333')

        self.__init_window_size(640, 480)
        self.__init_canvas()

    def __init_canvas(self):
        canvas = tk.Canvas(self)
        
        canvas.create_text(20, 30, anchor = tk.W, font = "Segoe\ Print",
            text = 'Most relationships seem so transitory')
        canvas.create_text(20, 70, anchor = tk.W, font = "Segoe\ Print",
            text = 'They\'re good but not the permanent one')
        canvas.create_text(20, 120, anchor = tk.W, font = "Segoe\ Print",
            text = 'Who doesn\'t long for someone to hold')
        canvas.create_text(20, 170, anchor = tk.W, font = "Segoe\ Print",
            text = 'Who knows how to love without being told')
        canvas.create_text(20, 220, anchor = tk.W, font = "Segoe\ Print",
            text = 'Somebody tell me why I\'m on my own')
        canvas.create_text(20, 270, anchor = tk.W, font = "Segoe\ Print",
            text = 'If there\'s a soulmate for everyone')

        canvas.pack(fill = tk.BOTH, expand = True)

    def __init_window_size(self, width = None, height = None):
        screen_width = self.master.winfo_screenwidth()
        screen_height = self.master.winfo_screenheight()

        if width is None:
            width = screen_width
        if height is None:
            height = screen_height

        x_coordinate = int((screen_width - width) / 2)
        y_coordinate = int((screen_height - height) / 2)

        self.master.geometry('{}x{}+{}+{}'.format(width, height, x_coordinate, y_coordinate))

if __name__ == '__main__':
    root = tk.Tk()
    main_frame = CanvasDraw(root)

    root.mainloop()

