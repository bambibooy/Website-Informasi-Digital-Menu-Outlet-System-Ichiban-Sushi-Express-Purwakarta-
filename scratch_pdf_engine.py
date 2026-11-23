# -*- coding: utf-8 -*-
import zlib
import re
import os

class PDFEngine:
    def __init__(self, filename="PANDUAN_UKK_ICHIBAN_SUSHI_LARAVEL10.pdf"):
        self.filename = filename
        self.width = 595.28   # A4 width in points
        self.height = 841.89  # A4 height in points
        self.margin_left = 40.0
        self.margin_right = 40.0
        self.margin_top = 45.0
        self.margin_bottom = 45.0
        self.content_width = self.width - self.margin_left - self.margin_right
        
        self.pages = []
        self.current_page_ops = []
        self.current_y = self.height - self.margin_top
        
        # Color definitions (r, g, b in 0.0 - 1.0)
        self.COLOR_PRIMARY = (0.769, 0.118, 0.141)    # Ichiban Red #C41E24
        self.COLOR_PRIMARY_DARK = (0.60, 0.08, 0.10)
        self.COLOR_PRIMARY_LIGHT = (0.988, 0.941, 0.945) # Tinted bg
        self.COLOR_INK = (0.12, 0.16, 0.23)          # Dark text #1E293B
        self.COLOR_MUTED = (0.38, 0.44, 0.52)        # Slate #64748B
        self.COLOR_LINE = (0.85, 0.88, 0.91)         # Border #CBD5E1
        self.COLOR_BG_CARD = (0.97, 0.98, 0.99)      # #F8FAFC
        self.COLOR_BG_CODE = (0.12, 0.15, 0.20)      # Dark code block bg #1E2433
        self.COLOR_TEXT_CODE = (0.92, 0.94, 0.96)    # White code text
        self.COLOR_WHITE = (1.0, 1.0, 1.0)
        self.COLOR_SUCCESS_BORDER = (0.13, 0.65, 0.38)
        self.COLOR_SUCCESS_BG = (0.94, 0.99, 0.96)
        
        # Helvetica character metrics (in 1/1000 em)
        self.char_widths = {
            ' ': 278, '!': 278, '"': 355, '#': 556, '$': 556, '%': 889, '&': 667, '\'': 191,
            '(': 333, ')': 333, '*': 389, '+': 584, ',': 278, '-': 333, '.': 278, '/': 278,
            '0': 556, '1': 556, '2': 556, '3': 556, '4': 556, '5': 556, '6': 556, '7': 556, '8': 556, '9': 556,
            ':': 278, ';': 278, '<': 584, '=': 584, '>': 584, '?': 556, '@': 1015,
            'A': 667, 'B': 667, 'C': 722, 'D': 722, 'E': 667, 'F': 611, 'G': 778, 'H': 722, 'I': 278,
            'J': 500, 'K': 667, 'L': 556, 'M': 833, 'N': 722, 'O': 778, 'P': 667, 'Q': 778, 'R': 722,
            'S': 667, 'T': 611, 'U': 722, 'V': 667, 'W': 944, 'X': 667, 'Y': 667, 'Z': 611,
            '[': 278, '\\': 278, ']': 278, '^': 469, '_': 556, '`': 222,
            'a': 556, 'b': 556, 'c': 500, 'd': 556, 'e': 556, 'f': 278, 'g': 556, 'h': 556, 'i': 222,
            'j': 222, 'k': 500, 'l': 222, 'm': 833, 'n': 556, 'o': 556, 'p': 556, 'q': 556, 'r': 333,
            's': 500, 't': 278, 'u': 556, 'v': 500, 'w': 722, 'x': 500, 'y': 500, 'z': 500,
            '{': 334, '|': 260, '}': 334, '~': 584
        }
        
        # Start first page
        self.new_page(is_first=True)

    def new_page(self, is_first=False):
        if self.current_page_ops:
            self.pages.append(self.current_page_ops)
            self.current_page_ops = []
        
        self.current_y = self.height - self.margin_top
        
        if not is_first:
            # Running header
            header_y = self.height - 28.0
            self.draw_text(self.margin_left, header_y, "PANDUAN UKK REKAYASA PERANGKAT LUNAK — ICHIBAN SUSHI (LARAVEL 10)", "F2", 8.0, self.COLOR_MUTED)
            self.draw_line(self.margin_left, header_y - 5.0, self.width - self.margin_right, header_y - 5.0, self.COLOR_LINE, 0.75)
            self.current_y = self.height - self.margin_top - 5.0

    def ensure_space(self, height_needed):
        if self.current_y - height_needed < self.margin_bottom:
            self.new_page()

    def get_text_width(self, text, font, size):
        if font in ('F3', 'F4'): # Courier
            return len(text) * (0.6 * size)
        total = 0
        factor = 1.05 if font == 'F2' else 1.0 # Bold is slightly wider
        for ch in text:
            w = self.char_widths.get(ch, 550) * factor
            total += (w / 1000.0) * size
        return total

    def escape_pdf_text(self, text):
        return text.replace('\\', '\\\\').replace('(', '\\(').replace(')', '\\)')

    def draw_text(self, x, y, text, font="F1", size=10.0, color=(0.1, 0.1, 0.1)):
        r, g, b = color
        safe_text = self.escape_pdf_text(text)
        op = f"BT /{font} {size:.2f} Tf {r:.3f} {g:.3f} {b:.3f} rg {x:.2f} {y:.2f} Td ({safe_text}) Tj ET"
        self.current_page_ops.append(op)

    def draw_line(self, x1, y1, x2, y2, color=(0.8, 0.8, 0.8), width=1.0):
        r, g, b = color
        op = f"{r:.3f} {g:.3f} {b:.3f} RG {width:.2f} w {x1:.2f} {y1:.2f} m {x2:.2f} {y2:.2f} l S"
        self.current_page_ops.append(op)

    def draw_rect(self, x, y, w, h, fill_color=None, stroke_color=None, stroke_width=1.0):
        ops = []
        if fill_color:
            r, g, b = fill_color
            ops.append(f"{r:.3f} {g:.3f} {b:.3f} rg")
        if stroke_color:
            r, g, b = stroke_color
            ops.append(f"{r:.3f} {g:.3f} {b:.3f} RG {stroke_width:.2f} w")
        
        ops.append(f"{x:.2f} {y:.2f} {w:.2f} {h:.2f} re")
        
        if fill_color and stroke_color:
            ops.append("B")
        elif fill_color:
            ops.append("f")
        elif stroke_color:
            ops.append("S")
            
        self.current_page_ops.append(" ".join(ops))

    def wrap_text(self, text, font, size, max_width):
        words = text.split(' ')
        lines = []
        cur_line = []
        cur_w = 0.0
        space_w = self.get_text_width(' ', font, size)
        
        for w in words:
            w_len = self.get_text_width(w, font, size)
            if cur_line and (cur_w + space_w + w_len > max_width):
                lines.append(" ".join(cur_line))
                cur_line = [w]
                cur_w = w_len
            else:
                if cur_line:
                    cur_w += space_w + w_len
                else:
                    cur_w = w_len
                cur_line.append(w)
        if cur_line:
            lines.append(" ".join(cur_line))
        return lines

    # High-level layout components
    def add_title_header(self, title, subtitle, badge="DOKUMEN BELAJAR UKK RPL"):
        h_box = 85.0
        self.ensure_space(h_box + 20)
        
        # Red background banner
        y_top = self.current_y
        self.draw_rect(self.margin_left, y_top - h_box, self.content_width, h_box, fill_color=self.COLOR_PRIMARY)
        
        # Gold accent bar on left
        self.draw_rect(self.margin_left, y_top - h_box, 6.0, h_box, fill_color=(1.0, 0.84, 0.0))
        
        # Badge
        self.draw_rect(self.margin_left + 16, y_top - 24, 160.0, 16.0, fill_color=(0.60, 0.08, 0.10))
        self.draw_text(self.margin_left + 22, y_top - 20, badge, "F2", 8.0, self.COLOR_WHITE)
        
        # Title
        self.draw_text(self.margin_left + 16, y_top - 46, title, "F2", 16.0, self.COLOR_WHITE)
        
        # Subtitle
        self.draw_text(self.margin_left + 16, y_top - 66, subtitle, "F1", 10.0, (1.0, 0.92, 0.92))
        
        self.current_y = y_top - h_box - 20.0

    def add_section_h1(self, text, badge_num=None):
        self.ensure_space(45)
        self.current_y -= 8.0
        y = self.current_y
        
        # Accent pill
        pill_w = 28.0 if badge_num else 4.0
        if badge_num:
            self.draw_rect(self.margin_left, y - 16.0, pill_w, 18.0, fill_color=self.COLOR_PRIMARY)
            self.draw_text(self.margin_left + 6.0, y - 13.0, badge_num, "F2", 11.0, self.COLOR_WHITE)
            tx = self.margin_left + pill_w + 10.0
        else:
            self.draw_rect(self.margin_left, y - 16.0, 4.0, 18.0, fill_color=self.COLOR_PRIMARY)
            tx = self.margin_left + 12.0
            
        self.draw_text(tx, y - 14.0, text.upper(), "F2", 12.5, self.COLOR_PRIMARY)
        self.draw_line(self.margin_left, y - 22.0, self.width - self.margin_right, y - 22.0, self.COLOR_PRIMARY, 1.2)
        
        self.current_y = y - 32.0

    def add_section_h2(self, text):
        self.ensure_space(32)
        y = self.current_y
        self.draw_rect(self.margin_left, y - 12.0, 3.0, 14.0, fill_color=self.COLOR_INK)
        self.draw_text(self.margin_left + 8.0, y - 11.0, text, "F2", 10.5, self.COLOR_INK)
        self.current_y = y - 22.0

    def add_paragraph(self, text, font="F1", size=9.5, color=None, space_after=8.0):
        if color is None:
            color = self.COLOR_INK
        lines = self.wrap_text(text, font, size, self.content_width)
        line_height = size * 1.35
        
        self.ensure_space(len(lines) * line_height + space_after)
        
        for l in lines:
            self.draw_text(self.margin_left, self.current_y - size, l, font, size, color)
            self.current_y -= line_height
            
        self.current_y -= space_after

    def add_bullet(self, bold_prefix, text, size=9.0, space_after=5.0):
        line_height = size * 1.35
        indent = 16.0
        usable_w = self.content_width - indent
        
        full_text = bold_prefix + " " + text if bold_prefix else text
        lines = self.wrap_text(full_text, "F1", size, usable_w)
        
        self.ensure_space(len(lines) * line_height + space_after)
        
        # Bullet dot
        dot_y = self.current_y - (size * 0.7)
        self.draw_rect(self.margin_left + 4.0, dot_y - 1.5, 3.0, 3.0, fill_color=self.COLOR_PRIMARY)
        
        for i, l in enumerate(lines):
            # If line starts with bold prefix, render prefix bold
            if i == 0 and bold_prefix and l.startswith(bold_prefix):
                rest = l[len(bold_prefix):]
                self.draw_text(self.margin_left + indent, self.current_y - size, bold_prefix, "F2", size, self.COLOR_INK)
                prefix_w = self.get_text_width(bold_prefix, "F2", size)
                self.draw_text(self.margin_left + indent + prefix_w, self.current_y - size, rest, "F1", size, self.COLOR_INK)
            else:
                self.draw_text(self.margin_left + indent, self.current_y - size, l, "F1", size, self.COLOR_INK)
            self.current_y -= line_height
            
        self.current_y -= space_after

    def add_callout(self, title, text, type_alert="note"):
        # Alert types: note (blue/gray), exam (red/primary), tip (green)
        border_color = self.COLOR_PRIMARY if type_alert == "exam" else (self.COLOR_SUCCESS_BORDER if type_alert == "tip" else self.COLOR_MUTED)
        bg_color = self.COLOR_PRIMARY_LIGHT if type_alert == "exam" else (self.COLOR_SUCCESS_BG if type_alert == "tip" else self.COLOR_BG_CARD)
        
        size = 8.5
        line_height = size * 1.35
        usable_w = self.content_width - 24.0
        lines = self.wrap_text(text, "F1", size, usable_w)
        
        h_box = 18.0 + (len(lines) * line_height) + 12.0
        self.ensure_space(h_box + 10.0)
        
        y_top = self.current_y
        self.draw_rect(self.margin_left, y_top - h_box, self.content_width, h_box, fill_color=bg_color, stroke_color=self.COLOR_LINE, stroke_width=0.75)
        # Left accent stripe
        self.draw_rect(self.margin_left, y_top - h_box, 4.0, h_box, fill_color=border_color)
        
        # Title
        self.draw_text(self.margin_left + 12.0, y_top - 14.0, title.upper(), "F2", 9.0, border_color)
        
        # Lines
        cur_text_y = y_top - 26.0
        for l in lines:
            self.draw_text(self.margin_left + 12.0, cur_text_y, l, "F1", size, self.COLOR_INK)
            cur_text_y -= line_height
            
        self.current_y = y_top - h_box - 10.0

    def add_code_block(self, title, code_lines):
        size = 7.8
        line_height = 11.5
        h_box = 20.0 + (len(code_lines) * line_height) + 8.0
        
        self.ensure_space(h_box + 10.0)
        
        y_top = self.current_y
        # Box background
        self.draw_rect(self.margin_left, y_top - h_box, self.content_width, h_box, fill_color=self.COLOR_BG_CODE, stroke_color=self.COLOR_LINE, stroke_width=0.5)
        # Header strip
        self.draw_rect(self.margin_left, y_top - 18.0, self.content_width, 18.0, fill_color=(0.16, 0.20, 0.26))
        self.draw_text(self.margin_left + 10.0, y_top - 13.0, title, "F4", 8.0, (0.75, 0.82, 0.90))
        
        # Code content
        cur_code_y = y_top - 30.0
        for line in code_lines:
            # Highlight comments
            color = (0.55, 0.65, 0.75) if line.strip().startswith('//') or line.strip().startswith('#') else self.COLOR_TEXT_CODE
            self.draw_text(self.margin_left + 12.0, cur_code_y, line, "F3", size, color)
            cur_code_y -= line_height
            
        self.current_y = y_top - h_box - 10.0

    def add_table(self, headers, rows, col_widths):
        # Calculate row heights
        total_w = sum(col_widths)
        scale = self.content_width / total_w
        widths = [w * scale for w in col_widths]
        
        header_h = 22.0
        self.ensure_space(header_h + 35.0)
        
        y_top = self.current_y
        # Draw header bg
        self.draw_rect(self.margin_left, y_top - header_h, self.content_width, header_h, fill_color=self.COLOR_PRIMARY)
        
        cur_x = self.margin_left
        for i, h in enumerate(headers):
            self.draw_text(cur_x + 6.0, y_top - 15.0, h, "F2", 8.5, self.COLOR_WHITE)
            cur_x += widths[i]
            
        self.current_y = y_top - header_h
        
        # Draw rows
        for r_idx, row in enumerate(rows):
            # calculate height needed for this row
            max_lines = 1
            wrapped_cells = []
            for c_idx, cell in enumerate(row):
                lines = self.wrap_text(str(cell), "F1", 8.0, widths[c_idx] - 10.0)
                wrapped_cells.append(lines)
                if len(lines) > max_lines:
                    max_lines = len(lines)
                    
            row_h = max(18.0, max_lines * 11.0 + 6.0)
            self.ensure_space(row_h)
            
            y_row = self.current_y
            bg = self.COLOR_WHITE if r_idx % 2 == 0 else self.COLOR_BG_CARD
            self.draw_rect(self.margin_left, y_row - row_h, self.content_width, row_h, fill_color=bg, stroke_color=self.COLOR_LINE, stroke_width=0.5)
            
            cur_x = self.margin_left
            for c_idx, lines in enumerate(wrapped_cells):
                cell_y = y_row - 11.0
                for l in lines:
                    font = "F2" if c_idx == 0 else "F1"
                    color = self.COLOR_PRIMARY if c_idx == 0 else self.COLOR_INK
                    self.draw_text(cur_x + 6.0, cell_y, l, font, 8.0, color)
                    cell_y -= 11.0
                cur_x += widths[c_idx]
                
            self.current_y = y_row - row_h
            
        self.current_y -= 12.0

    def add_qa_item(self, num, question, answer):
        self.ensure_space(55.0)
        
        # Question box
        q_lines = self.wrap_text(f"Q{num}. {question}", "F2", 9.5, self.content_width - 16.0)
        q_h = len(q_lines) * 13.0 + 8.0
        
        y_q = self.current_y
        self.draw_rect(self.margin_left, y_q - q_h, self.content_width, q_h, fill_color=self.COLOR_PRIMARY_LIGHT, stroke_color=self.COLOR_LINE, stroke_width=0.75)
        self.draw_rect(self.margin_left, y_q - q_h, 3.5, q_h, fill_color=self.COLOR_PRIMARY)
        
        cur_qy = y_q - 11.0
        for l in q_lines:
            self.draw_text(self.margin_left + 10.0, cur_qy, l, "F2", 9.5, self.COLOR_PRIMARY_DARK)
            cur_qy -= 13.0
            
        self.current_y = y_q - q_h - 6.0
        
        # Answer
        ans_lines = self.wrap_text(answer, "F1", 9.0, self.content_width - 16.0)
        ans_h = len(ans_lines) * 12.5 + 4.0
        self.ensure_space(ans_h + 8.0)
        
        cur_ay = self.current_y - 9.0
        for l in ans_lines:
            self.draw_text(self.margin_left + 10.0, cur_ay, l, "F1", 9.0, self.COLOR_INK)
            cur_ay -= 12.5
            
        self.current_y = cur_ay - 6.0

    def build(self):
        if self.current_page_ops:
            self.pages.append(self.current_page_ops)
            self.current_page_ops = []
            
        total_pages = len(self.pages)
        
        # Stamp page numbers on all pages
        footer_y = 26.0
        for p_idx, page_ops in enumerate(self.pages):
            p_num = p_idx + 1
            footer_text = f"Halaman {p_num} dari {total_pages}"
            sub_text = "UKK Rekayasa Perangkat Lunak — Web Ichiban Sushi"
            
            # Draw footer line
            line_op = f"0.850 0.880 0.910 RG 0.75 w {self.margin_left:.2f} {footer_y + 12.0:.2f} m {self.width - self.margin_right:.2f} {footer_y + 12.0:.2f} l S"
            
            # Left subtext
            left_op = f"BT /F1 8.00 Tf 0.380 0.440 0.520 rg {self.margin_left:.2f} {footer_y:.2f} Td ({self.escape_pdf_text(sub_text)}) Tj ET"
            
            # Right page number
            r_len = self.get_text_width(footer_text, "F2", 8.0)
            rx = self.width - self.margin_right - r_len
            right_op = f"BT /F2 8.00 Tf 0.769 0.118 0.141 rg {rx:.2f} {footer_y:.2f} Td ({self.escape_pdf_text(footer_text)}) Tj ET"
            
            page_ops.append(line_op)
            page_ops.append(left_op)
            page_ops.append(right_op)

        # Generate PDF objects
        objects = []
        
        # 1 0 obj: Catalog
        objects.append(b"<< /Type /Catalog /Pages 2 0 R >>")
        
        # 2 0 obj: Pages
        kids_refs = []
        page_obj_start = 3
        # Page objects layout:
        # For each page: Page object (odd), Content stream object (even)
        # Fonts: will be at the end
        page_indices = []
        content_indices = []
        
        cur_obj_id = 3
        for _ in range(total_pages):
            page_indices.append(cur_obj_id)
            cur_obj_id += 1
            content_indices.append(cur_obj_id)
            cur_obj_id += 1
            
        font1_id = cur_obj_id
        font2_id = cur_obj_id + 1
        font3_id = cur_obj_id + 2
        font4_id = cur_obj_id + 3
        
        kids_str = " ".join([f"{pid} 0 R" for pid in page_indices])
        objects.append(f"<< /Type /Pages /Kids [{kids_str}] /Count {total_pages} >>".encode('ascii'))
        
        # Build page & content objects
        for p_idx in range(total_pages):
            p_obj_id = page_indices[p_idx]
            c_obj_id = content_indices[p_idx]
            
            page_def = f"<< /Type /Page /Parent 2 0 R /MediaBox [0 0 {self.width:.2f} {self.height:.2f}] /Contents {c_obj_id} 0 R " \
                       f"/Resources << /Font << /F1 {font1_id} 0 R /F2 {font2_id} 0 R /F3 {font3_id} 0 R /F4 {font4_id} 0 R >> >> >>"
            objects.append(page_def.encode('ascii'))
            
            stream_data = "\n".join(self.pages[p_idx]).encode('utf-8')
            compressed = zlib.compress(stream_data)
            
            stream_obj = f"<< /Length {len(compressed)} /Filter /FlateDecode >>\nstream\n".encode('ascii') + compressed + b"\nendstream"
            objects.append(stream_obj)
            
        # Font objects
        objects.append(b"<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>")
        objects.append(b"<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold >>")
        objects.append(b"<< /Type /Font /Subtype /Type1 /BaseFont /Courier >>")
        objects.append(b"<< /Type /Font /Subtype /Type1 /BaseFont /Courier-Bold >>")
        
        # Write PDF file
        with open(self.filename, 'wb') as f:
            f.write(b"%PDF-1.4\n%\xe2\xe3\xcf\xd3\n")
            offsets = [0]
            
            for i, obj in enumerate(objects):
                obj_id = i + 1
                pos = f.tell()
                offsets.append(pos)
                f.write(f"{obj_id} 0 obj\n".encode('ascii'))
                f.write(obj)
                f.write(b"\nendobj\n")
                
            xref_pos = f.tell()
            f.write(f"xref\n0 {len(objects) + 1}\n".encode('ascii'))
            f.write(b"0000000000 65535 f \n")
            for off in offsets[1:]:
                f.write(f"{off:010d} 00000 n \n".encode('ascii'))
                
            f.write(f"trailer\n<< /Size {len(objects) + 1} /Root 1 0 R >>\n".encode('ascii'))
            f.write(f"startxref\n{xref_pos}\n%%EOF\n".encode('ascii'))
            
        print(f"PDF berhasil dibuat: {self.filename} ({os.path.getsize(self.filename)} bytes, {total_pages} halaman)")
        return self.filename
