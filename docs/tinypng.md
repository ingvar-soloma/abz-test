# TinyPNG API Token Instructions

### To get an API token for TinyPNG:
1. Go to the [TinyPNG Developer Portal](https://tinypng.com): TinyPNG Developers
2. Sign Up or Log In: Create an account or log in if you already have one.
3. Create an API Key: After logging in, navigate to the API section and generate a new API key.
4. Save the API Key: Copy the generated key and store it in your Laravel .env file like this:
```dotenv
TINYPNG_API_KEY=your-api-key-here
```
5. Clear the Config Cache (if needed):
```bash
php artisan config:clear
```

---
### TinyPngService Class Description
The TinyPngService class is responsible for optimizing and resizing images using the TinyPNG API in a Laravel application.

#### Methods:
1. `processAndOptimizePhoto($photo)`: Saves, optimizes, and returns the image path.
2. `optimizeAndResizeWithTinyPNG($imagePath)`: Compresses & resizes to 70x70, stores, and returns the path.
---
### Usage Example:
```php
use App\Http\Services\TinyPngService;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload(Request $request, TinyPngService $tinyPngService)
    {
        $request->validate(['photo' => 'required|image']);

        $optimizedPath = $tinyPngService->processAndOptimizePhoto($request->file('photo'));

        return response()->json(['success' => true, 'path' => asset('storage/' . $optimizedPath)]);
    }
}

```
