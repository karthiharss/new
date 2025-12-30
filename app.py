from flask import Flask, request, render_template
import os
import numpy as np
from PIL import Image
from tensorflow.keras.applications.mobilenet_v2 import MobileNetV2, preprocess_input
from tensorflow.keras.preprocessing.image import img_to_array
from numpy.linalg import norm

app = Flask(__name__)

UPLOAD_FOLDER = "static/uploads"
DATASET_FOLDER = "static/dataset"

os.makedirs(UPLOAD_FOLDER, exist_ok=True)

model = MobileNetV2(weights="imagenet", include_top=False, pooling="avg")

def extract_features(img_path):
    img = Image.open(img_path).convert("RGB").resize((224, 224))
    img_array = img_to_array(img)
    img_array = np.expand_dims(img_array, axis=0)
    img_array = preprocess_input(img_array)
    features = model.predict(img_array)
    return features[0]

@app.route("/")
def index():
    return render_template("report_missing_pet.html")

@app.route("/submit", methods=["POST"])
def submit():
    if "pet_image" not in request.files:
        return "No image uploaded!"

    image = request.files["pet_image"]
    filename = image.filename
    upload_path = os.path.join(UPLOAD_FOLDER, filename)
    image.save(upload_path)

    uploaded_features = extract_features(upload_path)

    # Compare with dataset
    best_matches = []
    threshold = 0.4  # similarity threshold
    for file in os.listdir(DATASET_FOLDER):
        dataset_path = os.path.join(DATASET_FOLDER, file)
        dataset_features = extract_features(dataset_path)
        similarity = np.dot(uploaded_features, dataset_features) / (
            norm(uploaded_features) * norm(dataset_features)
        )
        if similarity >= threshold:
            best_matches.append({"file": file, "score": round(float(similarity), 3)})

    return render_template("result.html",
                           uploaded_image=filename,
                           matches=best_matches)
