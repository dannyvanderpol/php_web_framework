"""
Count PHP files and the number of code lines.
"""

import os


ROOT_PATH = os.path.abspath(os.path.join(os.path.dirname(__file__), ".."))

n_php_files = 0
n_code_lines = 0
for current_folder, sub_folders, filenames in os.walk(ROOT_PATH):
    sub_folders.sort()
    for filename in filenames:
        if filename.endswith(".php"):
            n_php_files += 1
            full_path = os.path.join(current_folder, filename)
            print(full_path)
            with open(full_path, "r") as fp:
                lines = fp.readlines()
            n_code_lines += len(list(filter(lambda x: x.strip() != "", lines)))

print(f"{n_php_files} PHP files with {n_code_lines} code lines")
