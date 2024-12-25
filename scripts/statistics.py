"""
Count PHP files and the number of code lines.
"""

import math
import os


ROOT_PATH = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))

n_php_files = 0
n_code_lines = 0
n_code_size = 0
for current_folder, sub_folders, filenames in os.walk(ROOT_PATH):
    sub_folders.sort()
    for filename in filenames:
        if filename.endswith(".php"):
            n_php_files += 1
            full_path = os.path.join(current_folder, filename)
            with open(full_path, "r") as fp:
                lines = fp.readlines()
            n_lines = len(list(filter(lambda line: (
                # Skip empty lines
                line.strip() != "" and
                # Skip comments
                not line.strip().startswith("//") and
                not line.strip().startswith("/*") and
                not line.strip().startswith("*")
            ), lines)))
            size = os.path.getsize(full_path)
            print(full_path, n_lines, size)
            n_code_lines += n_lines
            n_code_size += size

n_code_size = math.ceil(n_code_size / 1024)
print(f"{n_php_files} PHP files with {n_code_lines} code lines in {n_code_size} KB")
