const fs = require('fs');
const path = require('path');
const sharp = require('sharp');

// Direktori target yang berisi gambar
const TARGET_DIRS = [
  path.join(__dirname, 'public', 'img'),
  path.join(__dirname, 'src', 'img')
];

// Kata kunci untuk mendeteksi foto profil/tim agar di-resize lebih kecil (500px)
const PROFILE_KEYWORDS = [
  'nisrina', 'arik', 'lely', 'dito', 'ipul', 'azzam', 'wahyu', 'dian', 'team', 'avatar', 'profile'
];

// Batasan ukuran file (dalam bytes) untuk memicu optimasi (misalnya > 200KB)
const MIN_SIZE_TO_OPTIMIZE = 200 * 1024; 

async function optimizeImage(filePath) {
  const fileExt = path.extname(filePath).toLowerCase();
  if (!['.jpg', '.jpeg', '.png'].includes(fileExt)) return;

  const stats = fs.statSync(filePath);
  if (stats.size < MIN_SIZE_TO_OPTIMIZE) {
    // Abaikan file yang sudah kecil
    return;
  }

  const fileName = path.basename(filePath);
  const fileDir = path.dirname(filePath);
  const nameWithoutExt = path.basename(filePath, fileExt);
  
  // File output webp
  const outputFilePath = path.join(fileDir, `${nameWithoutExt}.webp`);

  // Tentukan lebar maksimal berdasarkan nama file/path
  const isProfile = PROFILE_KEYWORDS.some(keyword => 
    fileName.toLowerCase().includes(keyword) || fileDir.toLowerCase().includes('team')
  );
  
  const maxWidth = isProfile ? 500 : 1200;

  console.log(`\nProcessing: ${filePath}`);
  console.log(`Original Size: ${(stats.size / 1024 / 1024).toFixed(2)} MB`);
  console.log(`Targeting profile size: ${isProfile ? 'Yes (max 500px width)' : 'No (max 1200px width)'}`);

  try {
    const image = sharp(filePath);
    const metadata = await image.metadata();

    let pipeline = image;

    // Lakukan resize jika lebar gambar melebihi maxWidth
    if (metadata.width && metadata.width > maxWidth) {
      console.log(`Resizing width from ${metadata.width}px to ${maxWidth}px`);
      pipeline = pipeline.resize({ width: maxWidth, withoutEnlargement: true });
    }

    // Konversi ke webp dengan kualitas 80
    await pipeline
      .webp({ quality: 80 })
      .toFile(outputFilePath);

    const newStats = fs.statSync(outputFilePath);
    const savings = stats.size - newStats.size;
    const savingsPercent = ((savings / stats.size) * 100).toFixed(1);

    console.log(`Optimized WebP: ${outputFilePath}`);
    console.log(`New Size: ${(newStats.size / 1024).toFixed(1)} KB`);
    console.log(`Savings: ${(savings / 1024 / 1024).toFixed(2)} MB (${savingsPercent}%)`);

    // Hapus file asli berukuran besar untuk mencegah pemborosan ruang
    fs.unlinkSync(filePath);
    console.log(`Removed original file: ${fileName}`);

  } catch (err) {
    console.error(`Error processing ${fileName}:`, err.message);
  }
}

function traverseDirectory(dir) {
  if (!fs.existsSync(dir)) return [];
  
  let results = [];
  const list = fs.readdirSync(dir);
  
  list.forEach(file => {
    const filePath = path.join(dir, file);
    const stat = fs.statSync(filePath);
    
    if (stat && stat.isDirectory()) {
      results = results.concat(traverseDirectory(filePath));
    } else {
      results.push(filePath);
    }
  });
  
  return results;
}

async function main() {
  console.log('=== Starting Image Optimization ===');
  let allFiles = [];
  
  TARGET_DIRS.forEach(dir => {
    console.log(`Scanning: ${dir}`);
    const files = traverseDirectory(dir);
    allFiles = allFiles.concat(files);
  });

  console.log(`Found ${allFiles.length} total files. Checking sizes and formats...`);

  for (const file of allFiles) {
    await optimizeImage(file);
  }

  console.log('\n=== Image Optimization Complete ===');
}

main();
