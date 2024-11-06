# Copyright 2020 Amazon.com, Inc. or its affiliates. All Rights Reserved.
# PDX-License-Identifier: MIT-0 (For details, see https://github.com/awsdocs/amazon-rekognition-custom-labels-developer-guide/blob/master/LICENSE-SAMPLECODE.)

import boto3
import io
from PIL import Image, ImageDraw, ImageFont

def display_image(image, response):
    # Ready image to draw bounding boxes on it.
    imgWidth, imgHeight = image.size
    draw = ImageDraw.Draw(image)

    # calculate and display bounding boxes for each detected custom label
    print('Detected custom labels:')
    for customLabel in response['CustomLabels']:
        print('Label ' + str(customLabel['Name']))
        print('Confidence ' + str(customLabel['Confidence']))
        if 'Geometry' in customLabel:
            box = customLabel['Geometry']['BoundingBox']
            left = imgWidth * box['Left']
            top = imgHeight * box['Top']
            width = imgWidth * box['Width']
            height = imgHeight * box['Height']

            fnt = ImageFont.truetype('/Library/Fonts/Arial.ttf', 50)  # Ajuste o caminho da fonte se necessário
            draw.text((left, top), customLabel['Name'], fill='#00d400', font=fnt)

            print('Left: ' + '{0:.0f}'.format(left))
            print('Top: ' + '{0:.0f}'.format(top))
            print('Label Width: ' + "{0:.0f}".format(width))
            print('Label Height: ' + "{0:.0f}".format(height))

            points = (
                (left, top),
                (left + width, top),
                (left + width, top + height),
                (left, top + height),
                (left, top)
            )
            draw.line(points, fill='#00d400', width=5)

    image.show()

def show_custom_labels(model, image, min_confidence):
    client = boto3.client('rekognition')

    # Convert the image to bytes
    image_bytes = io.BytesIO()
    image.save(image_bytes, format='JPEG')
    image_bytes.seek(0)

    # Call DetectCustomLabels
    response = client.detect_custom_labels(
        Image={'Bytes': image_bytes.getvalue()},
        MinConfidence=min_confidence,
        ProjectVersionArn=model
    )

    # Display the image with drawn bounding boxes
    display_image(image, response)

    return len(response['CustomLabels'])

def main():
    # Load the image from the root of the project
    photo_path = r"C:\Users\Usuário\Desktop\comunicaMao\rekognition\imagens\a2.jpg"
    image = Image.open(photo_path)
    
    model = 'arn:aws:rekognition:us-east-1:256433828779:project/libras-rekognition/version/libras-rekognition.2024-11-05T00.57.42/1730779063889'
    min_confidence = 1


    label_count = show_custom_labels(model, image, min_confidence)
    print("Custom labels detected: " + str(label_count))

if __name__ == "__main__":
    main()
