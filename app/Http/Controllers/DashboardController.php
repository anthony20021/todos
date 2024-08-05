<?php

namespace App\Http\Controllers;

use App\Models\Taches;
use App\Models\TachesListes;
use Illuminate\Http\Request;
use App\Models\Listes;
use App\Models\UsersListes;
use App\Models\User;

class DashboardController extends Controller
{
    public function getData() {
        $user = auth()->user();
        $listes = UsersListes::ofUser($user->id)->with('liste')->get();

        foreach ($listes as $usersListe) {
            $usersListe->owner = User::where('id', $usersListe->liste->owner)->first();
        }

        return response()->json([
            'user' => $user,
            'listes' => $listes
        ]);
    }

    public function getDataTache(Request $request){
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
    }

    public function addListe(Request $request){
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

    }

    public function addTask(Request $request){
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
    }

    public function deleteListe(Request $request){
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
    }

    public function deleteTask(Request $request){
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
    }

    public function shareListe(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|email',
            'list_id' => 'required|integer'
        ]);

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
    }

}


