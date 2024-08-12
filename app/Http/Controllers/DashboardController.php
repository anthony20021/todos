<?php

namespace App\Http\Controllers;

use App\Models\Taches;
use App\Models\TachesListes;
use Illuminate\Http\Request;
use App\Models\Listes;
use App\Models\UsersListes;
use App\Models\User;
use Illuminate\Contracts\Support\ValidatedData;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function getData() {
        try {
            $user = auth()->user();
            $listes = UsersListes::ofUser($user->id)->with('liste')->get();

            foreach ($listes as $usersListe) {
                $usersListe->owner = User::where('id', $usersListe->liste->owner)->first();
            }

            return response()->json([
                'user' => $user,
                'listes' => $listes
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error.'], 556);
        }
    }

    public function getDataTache(Request $request){
        try {
            $listeId = $request->input('list_id');

            $taches = TachesListes::ofListe($listeId)->with('tache')->get();
            $listeOwner = Listes::where('id', $listeId)->pluck('owner');
            if($listeOwner[0] ==  auth()->user()->id){
                $allUser = UsersListes::where('liste_id', $listeId)->with('user')->get();
                return response()->json([
                    'taches' => $taches,
                    'allUser' => $allUser
                ]);
            }
            return response()->json([
                'taches' => $taches,
                'allUser' => ''
            ]);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error.'], 556);
        }
    }

    public function addListe(Request $request){
        try {
            if (empty(trim($request->name))) {
                abort(400, 'The provided string is empty.');
            }
            $liste = new Listes();
            $liste->name = $request->name;
            $liste->owner = auth()->user()->id;
            $liste->save();

            $userListe = new UsersListes();
            $userListe->user_id = auth()->user()->id;
            $userListe->liste_id = $liste->id;
            $userListe->save();

            $listes = UsersListes::ofUser(auth()->user()->id)->with('liste')->get();

            foreach ($listes as $usersListe) {
                $usersListe->owner = User::where('id', $usersListe->liste->owner)->first();
            }

            return $listes;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error.'], 556);
        }

    }

    public function addTask(Request $request){
        try {
            if (empty(trim($request->input('task.name')))) {
                abort(400, "Nom manquant");
            }

            $task = new Taches();
            $task->name = $request->input('task.name');
            $task->user_id = auth()->user()->id;
            $task->checked = false;
            $task->save();

            $tachesListes = new TachesListes();
            $tachesListes->liste_id = $request->input('list_id');
            $tachesListes->tache_id = $task->id;
            $tachesListes->save();

            $tasks = TachesListes::ofListe($request->input('list_id'))->with('tache')->get();
            return $tasks;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'error.'], 556);
        }
    }

    public function deleteListe(Request $request){
        try {
            $userId = auth()->user()->id;
            $listeId = $request->input('list_id');
            $userListeId = Listes::where('id', $listeId)->pluck('owner');
            if($userId != $userListeId[0]){
                abort(555, "Vous n'avez pas les droits");
            }
            Listes::findOrFail($listeId)->delete();

            $listes = UsersListes::ofUser(auth()->user()->id)->with('liste')->get();

            foreach ($listes as $usersListe) {
                $usersListe->owner = User::where('id', $usersListe->liste->owner)->first();
            }

            return $listes;
        } catch (\Throwable $th) {
            return json_encode(['statut' => 'error']);
        }
    }

    public function deleteTask(Request $request){
        try {
            $userId = auth()->user()->id;
            $taskId = $request->input('task_id');
            $listeId = $request->input('list_id');
            $userListeId = Listes::where('id', $listeId)->pluck('owner');
            $userTaskId = Taches::where('id', $taskId)->pluck('user_id');
            if($userId != $userTaskId[0] && $userId != $userListeId[0]){
                abort(555, "Vous n'avez pas les droits");
            }
            Taches::findOrFail($taskId)->delete();
            $taches = TachesListes::ofListe($listeId)->with('tache')->get();
            return $taches;
        } catch (\Throwable $th) {
            return json_encode(['statut' => 'error']);
        }
    }

    public function modifTask(Request $request){
        try {
            $task = Taches::findOrFail($request->input('task_id'));
            $task->checked = $request->input('bool');
            $task->save();
            return json_encode(['statut' => 'ok']);
        } catch (\Throwable $th) {
            return json_encode(['statut' => 'errror']);
        }
    }

    public function leaveListe(Request $request){
        try {
            $request->validate([
                'list_id' => 'required|integer|exists:listes,id',
            ]);

            $userId = auth()->user()->id;
            $listeId = $request->input('list_id');

            $userListe = UsersListes::where('liste_id', $listeId)
                ->where('user_id', $userId)
                ->firstOrFail();

            $userListe->delete();
            $listes = UsersListes::ofUser($userId)->with('liste')->get();

            return $listes;
        } catch (\Throwable $th) {
            return json_encode(['statut' => 'error']);
        }
    }

    public function shareListe(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'email' => 'required|email',
                'list_id' => 'required|integer'
            ]);
            $userId = auth()->user()->id;
            $liste = Listes::where('id', $validatedData['list_id'])->firstOrFail();
            if ($liste->owner != $userId) {
                return response()->json(['message' => 'Vous ne pouvez pas partager votre liste.'], 555);
            }
            $user = User::where('email', $validatedData['email'])->first();

            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé.'], 556);
            }

            $existingUserListe = UsersListes::where('user_id', $user->id)
                ->where('liste_id', $validatedData['list_id'])
                ->first();

            if ($existingUserListe) {
                return response()->json(['message' => 'La liste est déjà partagée avec cet utilisateur.'], 557);
            }

            $userListe = new UsersListes();
            $userListe->user_id = $user->id;
            $userListe->liste_id = $validatedData['list_id'];
            $userListe->save();

            $allUser = UsersListes::where('liste_id', $validatedData['list_id'])->with('user')->get();

            return $allUser;
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Une erreur inattendue s\'est produite.'], 500);
        }
    }

    public function putListe(Request $request){
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Utilisateur non trouvé.'], 556);
            }
            $validatedData = $request->validate([
                'list_id' => 'required|integer',
                'name' => 'required|string',
                'style' => 'required|integer',
            ]);

            $liste = Listes::where('id', $validatedData['list_id'])->firstOrFail();
            if($liste->owner != $user->id){
                abort(555, "Vous n'avez pas les droits");
            }
            $liste->name = $validatedData['name'];
            $liste->style = $validatedData['style'];
            $liste->save();
            $result = [
                'statut' => 'ok',
                'liste' => $liste
            ];
            return json_encode($result);

        } catch (\Throwable $th) {
            return json_encode(['statut' => 'error']);
        }

    }

}


