@extends('layouts.admin')

@section('content')
    <div class="py-4">
        <div class="container-fluid px-4">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark">Manage Categories</h2>
                    <p class="text-muted">Atur kategori jasa yang tersedia di PeerSkill</p>
                </div>
                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary fw-bold">
                    + Add New Category
                </a>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="ps-4 py-3">No</th>
                                    <th>Category Name</th>
                                    <th>Slug</th>
                                    <th>Total Gigs</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($categories as $index => $category)
                                    <tr>
                                        <td class="ps-4">{{ $index + 1 }}</td>
                                        <td class="fw-bold text-primary">{{ $category->name }}</td>
                                        <td class="text-muted">/{{ $category->slug }}</td>
                                        <td>
                                            <span class="badge bg-secondary rounded-pill">
                                                {{ $category->gigs->count() }} Gigs
                                            </span>
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.categories.edit', $category->id) }}"
                                                    class="btn btn-sm btn-outline-warning">
                                                    Edit
                                                </a>

                                                <form id="delete-form-{{ $category->id }}"
                                                    action="{{ route('admin.categories.destroy', $category->id) }}"
                                                    method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDelete({{ $category->id }})">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data kategori ini akan dihapus permanen!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33', // Merah (Danger)
                cancelButtonColor: '#3085d6', // Biru (Primary) - Sesuaikan tema kamu
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Cari form berdasarkan ID unik, lalu submit
                    document.getElementById('delete-form-' + id).submit();
                }
            })
        }
    </script>
@endsection
