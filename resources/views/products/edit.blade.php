<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Task Manager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
  </head>
  <body>

    <div class="bg-dark py-3">
        <h3 class="text-white text-center">Task Manager</h3>
    </div>

    <div class="container">
        <div class="row justify-content-center mt-4">
            <div class="col-md-10 d-flex justify-content-end">
                <a href="{{ route('products.index') }}" class="btn btn-dark">Back</a>
            </div>
        </div>

        <div class="row d-flex justify-content-center">
            <div class="col-md-10">
                <div class="card border-0 shadow-lg my-4">
                    <div class="card-header bg-dark">
                        <h3 class="text-white">Edit Task</h3>
                    </div>
                    <form enctype="multipart/form-data" action="{{ route('products.update', $product->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label h5">Title</label>
                                <input value="{{ old('title', $product->title) }}" type="text" name="title" class="form-control form-control-lg @error('title') is-invalid @enderror" placeholder="Title">
                                @error('title')
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label h5">Description</label>
                                <textarea placeholder="Description" name="description" class="form-control" cols="30" rows="5">{{ old('description', $product->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label h5">Image</label>
                                <input type="file" name="image" class="form-control form-control-lg">
                                @if ($product->image)
                                    <img class="w-50 my-3" src="{{ asset('uploads/products/' . $product->image) }}" alt="Current Image">
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label h5">Due Date</label>
                                <input class="form-control" type="date" name="due_date" value="{{ old('due_date', $product->due_date) }}">
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" name="is_completed" value="1" {{ old('is_completed', $product->is_completed) ? 'checked' : '' }}>
                                <label class="form-check-label">Completed</label>
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-lg btn-primary">Update</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

  </body>
</html>
