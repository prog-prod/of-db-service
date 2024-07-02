<?php

namespace App\Http\Controllers\Admin;

use App\Contracts\OfUserRepositoryInterface;
use App\Facades\Helper;
use App\Http\Controllers\BaseController;
use App\Models\OfUser;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OfUserController extends BaseController
{
    public function index(Request $request, OfUserRepositoryInterface $ofUserRepository) {
        $request->validate([
            'search' => 'nullable|string',
        ]);
        $searchText = Helper::escapeElasticSearchValue($request->get('search'));
        if($searchText) {
            $ofUsers = $ofUserRepository->searchOfUsers($searchText);
        } else {
            $ofUsers = $ofUserRepository->searchTopOfUsers(1000);
        }
        return $this->showView('OfUsers/Index', [
            'ofUsers' => $ofUsers,
            'meta' => [
                'pageTitle' => 'OnlyFans Users',
                'breadcrumbs' => []
            ]
        ]);
    }

    public function update(Request $request, int $ofUserId): RedirectResponse
    {
        $ofUser = OfUser::findOrFail($ofUserId);
        $data = $request->validate([
            'free_trial_link' => 'nullable|string|url',
            'deleted' => 'nullable|boolean',
        ]);
        if($ofUser->update($data)) {
            return redirect()->back()->with('success', 'Updated successfully');
        }
        return redirect()->back()->with('error', 'Failed to update');
    }
}
