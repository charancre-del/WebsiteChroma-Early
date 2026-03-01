import os
import re

dir_path = r'C:\Users\chara\Documents\WebsiteChroma Early\plugins\earlystart-seo-pro\inc'

for root, dirs, files in os.walk(dir_path):
    for file in files:
        if file.endswith('.php'):
            filepath = os.path.join(root, file)
            with open(filepath, 'r', encoding='utf-8') as f:
                try:
                    content = f.read()
                except UnicodeDecodeError:
                    continue
                
            orig_content = content
            
            # Avoid matching ChildCare (Schema type), childcare_discovery (ID), and keys starting/ending with _
            # Replace lowercase
            content = re.sub(r'(?<![_\w])childcare(?![_\w])', 'pediatric therapy', content)
            # Replace Title Case
            content = re.sub(r'(?<![_\w])Childcare(?![_\w])', 'Pediatric Therapy', content)
            # Replace UPPERCASE
            content = re.sub(r'(?<![_\w])CHILDCARE(?![_\w])', 'PEDIATRIC THERAPY', content)
            
            if content != orig_content:
                with open(filepath, 'w', encoding='utf-8') as f:
                    f.write(content)
                print(f"Updated {file}")

print("Done")
