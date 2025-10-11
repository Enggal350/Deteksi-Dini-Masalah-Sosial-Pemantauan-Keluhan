import requests
import json


with open('test.json', 'r', encoding='utf-8') as f:
    data = json.load(f)
  
response = requests.post("http://127.0.0.1:5000/classify", json=data)

if response.status_code == 200:
    results = response.json()
    with open("hasil_aduan.json", "w", encoding="utf-8") as f:
        json.dump(results, f, ensure_ascii=False, indent=2)
        print("berhasil menyimpan")
else:
    print("API Error:", response.status_code)
