# Cutting Mat · Background Remover

A browser-based AI background remover. Upload a photo, and a local model traces the subject and makes the background transparent as a PNG.  
**Images are never uploaded to a server** — everything runs in your browser.

![Home screen](docs/screenshots/01-home.png)

## Features

- Drag-and-drop or click to upload JPG / PNG / WEBP
- Runs locally with `@huggingface/transformers` and `briaai/RMBG-1.4`
- Side-by-side view: **Original** vs **Cutout** (transparent background)
- One-click download of a transparent PNG

## Quick start

### Option 1: XAMPP or any static server

1. Place this repo under your web root (e.g. `C:\xampp\htdocs\remove-background`)
2. Start Apache (or any static file server)
3. Open in your browser:

```text
http://localhost/remove-background/
```

### Option 2: Open the file directly

Open `index.php` in Chrome, Edge, or Firefox (there is no PHP logic — treat it as HTML).  
The first background removal needs network access to download the model (cdn.jsdelivr.net, huggingface.co).

> Prefer a normal browser tab. Restricted embedded preview panels may block model loading.

## User manual

### 1. Open the app

You will see the cutting-mat style home screen with an upload area in the center.

![Upload home](docs/screenshots/01-home.png)

### 2. Upload a photo

- **Drag** an image onto the dashed box, or  
- **Click** the box and choose a file  

Supported: JPG, PNG, WEBP (ideally under 20MB).

After upload, the workspace opens: **Original** on the left, **Cutout** on the right (not processed yet).

![Workspace after upload](docs/screenshots/02-workspace.png)

### 3. Remove the background

Click the orange **Remove background** button.

- The first run downloads the AI model (progress: Downloading model files…)
- Then it analyzes the image (Analyzing image…)
- The right panel status shows **Processing…**

![Processing](docs/screenshots/03-processing.png)

### 4. Review and download

When finished:

- Status shows **Done**
- The Cutout panel uses a checkerboard to indicate transparency
- **Download PNG** becomes active — save as `original-name-cutout.png`

![Result and download](docs/screenshots/04-result.png)

### 5. Try another image

Click **Choose another** to return to the upload area and pick a new photo.

## Button reference

| Button | Action |
|--------|--------|
| Choose another | Clear the current image and pick again |
| Remove background | Run local AI background removal |
| Download PNG | Save the transparent PNG (after a successful run) |

## Technical notes

| Item | Detail |
|------|--------|
| Frontend | Single file `index.php` (plain HTML/CSS/JS) |
| AI | Hugging Face Transformers.js `background-removal` |
| Model | `briaai/RMBG-1.4` (fp32) |
| Privacy | Original and result stay on your device; no backend upload |

## Notes

1. **First use needs internet** to download the model; later runs are faster via browser cache.
2. Results vary with complex backgrounds, fine hair, or translucent objects.
3. Large images are slower; if something fails, try another photo or refresh the page.
4. Access to `cdn.jsdelivr.net` and `huggingface.co` is required.

## Repository layout

```text
remove-background/
├── index.php                 # App
├── README.md                 # This user manual
└── docs/screenshots/         # README screenshots
    ├── 01-home.png
    ├── 02-workspace.png
    ├── 03-processing.png
    └── 04-result.png
```

## License

MIT (change if you prefer another license)
