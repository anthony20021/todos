<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function profileUpload(Request $request)
    {
        try {
            // 1. Récupérer l'utilisateur connecté
            $user = auth()->user();
        
            // 2. Validation du formulaire
            $request->validate([
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ]);
        
            // 3. Vérifier si l'utilisateur a déjà une image de profil
            if ($user->profil_img) {
                // Trouver le document associé à l'ID stocké dans `profil_img`
                $existingDocument = Document::find($user->profil_img);
        
                if ($existingDocument) {
                    // Supprimer l'ancien fichier du disque
                    if (Storage::exists($existingDocument->path)) {
                        Storage::delete($existingDocument->path);
                    }
        
                    // Supprimer le document de la base (optionnel si on préfère réutiliser)
                    $existingDocument->delete();
                }
            }
        
            // 4. Gérer le nouvel upload
            if ($request->hasFile('image')) {
                // Récupérer le fichier
                $image = $request->file('image');
        
                // Définir un nom unique pour le fichier
                $imageName = 'profil_img_' . $user->id . '.' . $image->getClientOriginalExtension();
        
                // Définir le chemin de stockage dans le dossier privé, basé sur l'ID utilisateur
                $userDirectory = 'private/' . $user->id;
                $imagePath = $image->storeAs($userDirectory, $imageName);
                
                //Récupérer le type MIME du fichier
                $mimeType = $image->getMimeType();
    
                // 5. Créer un nouvel enregistrement dans la table `documents`
                $newDocument = Document::create([
                    'name' => 'profil_img_' . $user->id, // Nom logique pour identifier le fichier
                    'path' => $imagePath,                // Chemin du fichier dans `storage/app`
                    'type' => $mimeType,                // Catégorie du document
                ]);
        
                // 6. Mettre à jour la colonne `profil_img` dans la table `users`
                User::where('id', $user->id)->update([
                    'profil_img' => $newDocument->id, 
                ]);
        
                $userFinal = User::where('id', $user->id)->with('photo')->first();

                // 7. Retourner une réponse de succès
                return response()->json([
                    'status' => 'ok',
                    'message' => 'Image uploadée avec succès.',
                    'user' => $userFinal,
                ], 200);
            }
        
            // Si aucun fichier n'est envoyé
            return response()->json([
                'status' => 'ko',
                'message' => "Aucun fichier n'a été envoyé.",
            ], 400);
        }
        catch (\Throwable $th) {
            return response()->json([
                'status' => 'ko',
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function getProfileImage()
    {
        try {
            // 1. Récupérer l'utilisateur par son ID
            $user = auth()->user();
        
            // 2. Vérifier si l'utilisateur a une photo de profil
            if ($user->profil_img) {
                // Trouver le document associé à l'ID de la photo de profil
                $document = Document::find($user->profil_img);
        
                if ($document && Storage::exists($document->path)) {
                    // 3. Lire le fichier image depuis le stockage privé
                    $imageContent = Storage::get($document->path); // Lire le fichier
    
                    // 4. Retourner l'image en tant que réponse avec le bon type MIME
                    return response($imageContent, 200)
                        ->header('Content-Type', $document->type); // Utiliser le type MIME du document
                } else {
                    // Si le document n'existe pas ou a été supprimé
                    return response()->json([
                        'status' => 'ko',
                        'message' => 'Photo de profil introuvable.',
                    ], 404);
                }
            } else {
                // Si l'utilisateur n'a pas de photo de profil
                return response()->json([
                    'status' => 'ko',
                    'message' => 'L\'utilisateur n\'a pas de photo de profil.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'ko',
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
