import os
import uuid

def rename_images_with_uuid(directory_path):
    try:
        for filename in os.listdir(directory_path):
            file_path = os.path.join(directory_path, filename)

            if os.path.isfile(file_path) and filename.lower().endswith(('.png', '.jpg', '.jpeg', '.gif', '.bmp')):
                # Gera um UUID único
                new_filename = f"{uuid.uuid4()}{os.path.splitext(filename)[1]}"
                new_file_path = os.path.join(directory_path, new_filename)

                os.rename(file_path, new_file_path)
                print(f"Renomeado: {filename} -> {new_filename}")
        
        print("Renomeação concluída com sucesso.")
    except Exception as e:
        print(f"Ocorreu um erro: {e}")

# Caminho da pasta com as imagens
directory_path = r"C:\Users\Usuário\Desktop\comunicaMao\images\NAO"

rename_images_with_uuid(directory_path)
