import requests
from bs4 import BeautifulSoup
import time
from urllib.parse import urljoin

BASE_URL = "https://lapormasbup.purbalinggakab.go.id"
JELAJAH_URL = f"{BASE_URL}/jelajah-aduan" 

aduan_data = []
max_data = 1000
page = 1

headers = {
    "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) "
                  "AppleWebKit/537.36 (KHTML, like Gecko) "
                  "Chrome/141.0.0.0 Safari/537.36 Edg/141.0.0.0"
}

def ambil_nilai(soup, label):
    tag = soup.find('td', string=lambda t: t and label in t)
    if tag:
        nilai_td = tag.find_next_sibling('td')
        if nilai_td and nilai_td.text.strip() == ":":
            nilai_td = nilai_td.find_next_sibling('td')
        return nilai_td.text.strip() if nilai_td else None
    return None


seen_links = set()

while len(aduan_data) < max_data:
    print(f"Scraping halaman {page}...")
    url = f"{JELAJAH_URL}?page={page}"

    try:
        r = requests.get(url, headers=headers, timeout=10)
        r.raise_for_status()
    except requests.exceptions.RequestException as e:
        print(f"Gagal ambil halaman {page}: {e}")
        break

    soup = BeautifulSoup(r.text, "html.parser")


    links = list({
        urljoin(BASE_URL, a["href"])
        for a in soup.find_all("a", href=True)
        if "/detail-aduan/" in a["href"]
    })

    if not links:
        print("Tidak ada link aduan di halaman ini.")
        break

    for link in links:
        if len(aduan_data) >= max_data:
            break
        if link in seen_links:
            continue
        seen_links.add(link)

        try:
            r2 = requests.get(link, headers=headers, timeout=10)
            r2.raise_for_status()
        except requests.exceptions.RequestException as e:
            print(f" Gagal buka detail {link}: {e}")
            continue

        soup2 = BeautifulSoup(r2.text, "html.parser")

        isi_aduan = ambil_nilai(soup2, "Isi Aduan")
        sektor = ambil_nilai(soup2, "Sektor")

        aduan_data.append({
            "isi": isi_aduan or "(Kosong)",
            "sektor": sektor or "(Kosong)"
        })
        print(f"Ambil data ke-{len(aduan_data)} ")

        time.sleep(0.5)

    page += 1


unique_aduan = []
seen_texts = set()
for a in aduan_data:
    if a['isi'] not in seen_texts:
        seen_texts.add(a['isi'])
        unique_aduan.append(a)
aduan_data = unique_aduan

with open("aduan_data.txt", "w", encoding="utf-8") as f:
    for i, a in enumerate(aduan_data, 1):
        f.write(f"{i}. Aduan: {a['isi']}\n")
        f.write(f"Sektor: {a['sektor']}\n")
        f.write("=" * 80 + "\n")

print(f"\n Total data diambil: {len(aduan_data)}")
print("Data disimpan ke file aduan.txt")
