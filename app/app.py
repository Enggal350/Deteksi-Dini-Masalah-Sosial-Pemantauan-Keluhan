import joblib
from flask import Flask,  request, jsonify
from flask_cors import CORS
import json
import os

app = Flask(__name__)
CORS(app)

with open("../model/model_saring_aduan.pkl", "rb") as model1:
    model_aduan = joblib.load(model1)

with open("../model/model_kategori.pkl", "rb") as model2:
    model_kategori = joblib.load(model2)



@app.route('/')
def index():
    return {"return":"SUCCES",
            "message":"Service is up"},200

@app.route('/proses', methods=['POST'])
def proses():
    text = request.form.get("text") 
    if not text:
        return jsonify({"error": "Teks tidak boleh kosong"}), 400

   
    predict_proba_aduan = model_aduan.predict_proba([text])[0]
    predict_aduan = model_aduan.predict([text])[0]
    probabilitas_aduan = dict(zip(model_aduan.classes_, predict_proba_aduan))

   
    if predict_aduan == 'Aduan':
        predict_proba_kat = model_kategori.predict_proba([text])[0]
        pred_kategori = model_kategori.predict([text])[0]
        probabilitas_kategori = dict(zip(model_kategori.classes_, predict_proba_kat))
        result = {
            'text': text,
            'aduan': True,
            'probabilitas_aduan': probabilitas_aduan,
            'kategori': pred_kategori,
            'probabilitas_kategori': probabilitas_kategori
        }
    else:
        result = {
            'text': text,
            'aduan': False,
            'probabilitas_aduan': probabilitas_aduan
        }

    return jsonify(result)


@app.route('/classify', methods=['POST'])
def classify():
    data = request.json
    results = []
    for item in data:
        text = item['text']
        predict_proba_aduan = model_aduan.predict_proba([text])[0]
        predict_aduan = model_aduan.predict([text])[0]
        probabilitas_aduan = dict(zip(model_aduan.classes_, predict_proba_aduan))
        if predict_aduan == 'Aduan':
            predict_proba_kat = model_kategori.predict_proba([text])[0]
            pred_kategori = model_kategori.predict([text])[0]
            probabilitas_kategori = dict(zip(model_kategori.classes_, predict_proba_kat))
            results.append({
                'text': text,
                'aduan': True,
                'probabilitas_aduan':probabilitas_aduan,
                'kategori': pred_kategori,
                "probabilitas_kategori":probabilitas_kategori
            })
        else: 
            results.append({
                'text': text,
                'aduan': False,
                'probabilitas_aduan':probabilitas_aduan
            })
    return jsonify(results)
@app.route("/latest", methods=["GET"])
def latest():
    path = os.path.join(os.getcwd(), "hasil_aduan.json")
    if not os.path.exists(path):
        return jsonify({"message": "Belum ada data"}), 404

    with open(path, "r", encoding="utf-8") as f:
        data = json.load(f)

    if not data:
        return jsonify({"message": "File kosong"}), 404

    return jsonify(data[-1]) 
    


if __name__ == '__main__':
    app.run(debug=True)
