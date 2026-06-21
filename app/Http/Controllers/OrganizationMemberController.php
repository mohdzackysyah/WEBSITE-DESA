<?php

namespace App\Http\Controllers;

use App\Models\OrganizationMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class OrganizationMemberController extends Controller
{
    public function index()
    {
        $members = OrganizationMember::orderBy('sort_order', 'asc')->paginate(10);
        return view('admin.content.members_index', compact('members'));
    }

    public function create()
    {
        return view('admin.content.member_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|integer|in:1,2,3,4',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $member = new OrganizationMember($request->only(['name', 'position', 'level', 'description', 'sort_order']));

        if ($request->hasFile('photo')) {
            $uploadResult = \App\Helpers\UploadHelper::processUpload($request->file('photo'), ['jpeg', 'png', 'jpg', 'webp'], true, 'members');
            if (!$uploadResult['success']) {
                return back()->withErrors(['photo' => implode(' ', $uploadResult['errors'])])->withInput();
            }
            $member->photo = $uploadResult['path'];
        }

        $member->save();

        return redirect()->route('admin.content.members.index')->with('success', 'Anggota struktur organisasi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $member = OrganizationMember::findOrFail($id);
        return view('admin.content.member_form', compact('member'));
    }

    public function update(Request $request, $id)
    {
        $member = OrganizationMember::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'position' => 'required|string|max:255',
            'level' => 'required|integer|in:1,2,3,4',
            'description' => 'nullable|string|max:1000',
            'sort_order' => 'required|integer|min:0',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp',
        ]);

        $member->fill($request->only(['name', 'position', 'level', 'description', 'sort_order']));

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            $oldPath = public_path(ltrim($member->photo, '/'));
            if ($member->photo && file_exists($oldPath)) {
                @unlink($oldPath);
            }

            $uploadResult = \App\Helpers\UploadHelper::processUpload($request->file('photo'), ['jpeg', 'png', 'jpg', 'webp'], true, 'members');
            if (!$uploadResult['success']) {
                return back()->withErrors(['photo' => implode(' ', $uploadResult['errors'])])->withInput();
            }
            $member->photo = $uploadResult['path'];
        }

        $member->save();

        return redirect()->route('admin.content.members.index')->with('success', 'Anggota struktur organisasi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $member = OrganizationMember::findOrFail($id);

        // Delete photo from disk
        $oldPath = public_path(ltrim($member->photo, '/'));
        if ($member->photo && file_exists($oldPath)) {
            @unlink($oldPath);
        }

        $member->delete();

        return redirect()->route('admin.content.members.index')->with('success', 'Anggota struktur organisasi berhasil dihapus.');
    }
}
