import pickle
import sys
import json
import numpy as np

def predict_health_trend(model_path, county, health_level, nurse_experience):
    """Predict health trends - returns ONLY trend and confidence"""
    try:
        # Load the model
        with open(model_path, 'rb') as f:
            model_package = pickle.load(f)
        
        model = model_package['model']
        le_county = model_package['le_county']
        le_health_level = model_package['le_health_level']
        le_trend = model_package['le_trend']
        
        # Encode inputs
        try:
            county_enc = le_county.transform([county])[0]
        except:
            county_enc = 0
        
        try:
            health_enc = le_health_level.transform([health_level])[0]
        except:
            health_enc = 0
        
        # Create feature array
        features = np.array([[county_enc, health_enc, nurse_experience]])
        
        # Predict
        prediction_encoded = model.predict(features)[0]
        trend = le_trend.inverse_transform([prediction_encoded])[0]
        
        # Get confidence
        probabilities = model.predict_proba(features)[0]
        confidence = float(max(probabilities) * 100)
        
        # Return ONLY trend and confidence
        return {
            'trend': trend.capitalize(),
            'confidence': round(confidence, 1)
        }
    
    except Exception as e:
        return {
            'trend': 'Error',
            'confidence': 0.0
        }

if __name__ == "__main__":
    model_path = sys.argv[1]
    county = sys.argv[2]
    health_level = sys.argv[3]
    nurse_experience = float(sys.argv[4])
    
    result = predict_health_trend(model_path, county, health_level, nurse_experience)
    print(json.dumps(result))