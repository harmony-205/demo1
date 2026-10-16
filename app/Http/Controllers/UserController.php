<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    private const SORTABLE = ['id', 'name', 'email', 'role', 'trang_thai', 'created_at'];
    private const PER_PAGE_OPTIONS = [10, 20, 50];

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = Validator::make($request->query(), [
            'keyword' => 'nullable|string|max:100',
            'role' => 'nullable|in:' . User::ROLE_ADMIN . ',' . User::ROLE_TEACHER,
            'trang_thai' => 'nullable|in:0,1',
            'sort' => 'nullable|in:' . implode(',', self::SORTABLE),
            'direction' => 'nullable|in:asc,desc',
            'perPage' => 'nullable|in:' . implode(',', self::PER_PAGE_OPTIONS),
        ])->valid();

        $sort = $filters['sort'] ?? 'id';
        $direction = $filters['direction'] ?? 'desc';
        $perPage = (int) ($filters['perPage'] ?? 10);

        $users = User::query()
            ->when(isset($filters['keyword']), function ($query) use ($filters) {
                $kw = '%' . $filters['keyword'] . '%';
                $query->where(function ($q) use ($kw) {
                    $q->where('name', 'like', $kw)
                        ->orWhere('email', 'like', $kw)
                        ->orWhere('so_dien_thoai', 'like', $kw);
                });
            })
            ->when(isset($filters['role']), fn ($query) => $query->where('role', $filters['role']))
            ->when(isset($filters['trang_thai']), fn ($query) => $query->where('trang_thai', $filters['trang_thai']))
            ->orderBy($sort, $direction)
            ->paginate($perPage)
            ->withQueryString();

        return view('users.index', [
            'users' => $users,
            'filters' => $filters,
            'sort' => $sort,
            'direction' => $direction,
            'perPage' => $perPage,
            'sortable' => self::SORTABLE,
            'perPageOptions' => self::PER_PAGE_OPTIONS,
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create', [
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : true;

        User::create($data);

        return redirect()->route('users.index')->with('success', 'Người dùng đã được tạo thành công.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', ['user' => $user]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', [
            'user' => $user,
            'roles' => User::ROLES,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $data['trang_thai'] = $request->has('trang_thai') ? (bool) $request->trang_thai : false;

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'Thông tin người dùng đã được cập nhật.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if (auth()->id() === $user->id) {
            return back()->with('error', 'Bạn không thể tự xóa tài khoản của chính mình.');
        }

        $user->delete();

        return back()->with('success', 'Người dùng đã được xóa thành công.');
    }
}
