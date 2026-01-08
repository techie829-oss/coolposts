# Cloudinary Storage Configuration

After installing the Cloudinary package, you need to configure your credentials.

## Steps to Configure

### 1. Get Your Cloudinary Credentials

1. Go to https://cloudinary.com/console
2. Copy your:
   - Cloud Name
   - API Key
   - API Secret

### 2. Add to `.env` File

Add these lines to your `.env` file (NOT `.env.example`):

```env
CLOUDINARY_CLOUD_NAME=your_cloud_name_here
CLOUDINARY_API_KEY=your_api_key_here
CLOUDINARY_API_SECRET=your_api_secret_here
CLOUDINARY_URL=cloudinary://your_api_key:your_api_secret@your_cloud_name
```

### 3. Set Cloudinary as Default (Optional)

To make Cloudinary the default storage, update this line in `.env`:

```env
FILESYSTEM_DISK=cloudinary
```

Or keep it as `local` and specify `cloudinary` disk when needed:

```php
Storage::disk('cloudinary')->put('file.jpg', $contents);
```

## Usage Examples

### Upload to Cloudinary

```php
// In your controller
$path = $request->file('image')->store('blog-images', 'cloudinary');

// Or explicitly
$path = Storage::disk('cloudinary')->putFile('blog-images', $request->file('image'));
```

### Get URL

```php
// Will automatically use Cloudinary URL if uploaded there
$url = Storage::url($path);

// Or use Cloudinary helper
$url = cloudinary()->getUrl($path);
```

### Transform Images (Cloudinary Feature)

```php
$url = cloudinary()->getUrl($path, [
    'transformation' => [
        'width' => 800,
        'height' => 600,
        'crop' => 'fill',
        'quality' => 'auto',
    ]
]);
```

## Existing Images

Images already stored locally will continue to work. New uploads will use Cloudinary (if set as default).

## Testing

Create a test blog post with an image to verify Cloudinary is working correctly.
