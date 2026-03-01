import re

file_path = r'C:\Users\chara\Documents\WebsiteChroma Early\plugins\chroma-agent-api\includes\routes\class-geo-routes.php'
with open(file_path, 'r', encoding='utf-8') as f:
    content = f.read()

# Make the specific string replacements
content = content.replace("chroma_agent_geo_feed_v2", "earlystart_agent_geo_feed_v2")
content = content.replace("'_chroma_", "'_earlystart_")
content = content.replace("chroma_faq", "earlystart_faq")
content = content.replace("chroma_seo", "earlystart_seo")
content = content.replace("chroma_llm", "earlystart_llm")

with open(file_path, 'w', encoding='utf-8') as f:
    f.write(content)
print("Updated successfully")
