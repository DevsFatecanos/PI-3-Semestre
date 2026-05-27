#!/usr/bin/env node
import imagemin from 'imagemin';
import imageminMozjpeg from 'imagemin-mozjpeg';
import imageminPngquant from 'imagemin-pngquant';
import imageminWebp from 'imagemin-webp';
import { fileURLToPath } from 'url';
import { dirname } from 'path';

const __filename = fileURLToPath(import.meta.url);
const __dirname = dirname(__filename);

const publicDir = `${__dirname}/../public`;

async function optimizeImages() {
    console.log('🖼️  Iniciando otimização de imagens...');

    try {
        // Otimizar JPGs e PNGs
        await imagemin([`${publicDir}/**/*.{jpg,jpeg,png}`], {
            destination: publicDir,
            plugins: [
                imageminMozjpeg({ quality: 80 }),
                imageminPngquant({
                    quality: [0.6, 0.8],
                    strip: true,
                    speed: 1,
                })
            ]
        });

        console.log('✅ JPGs e PNGs otimizados');

        // Converter para WebP
        await imagemin([`${publicDir}/**/*.{jpg,jpeg,png}`], {
            destination: publicDir,
            plugins: [
                imageminWebp({ quality: 75 })
            ]
        });

        console.log('✅ Imagens convertidas para WebP');
        console.log('🎉 Otimização concluída!');
    } catch (error) {
        console.error('❌ Erro ao otimizar imagens:', error);
        process.exit(1);
    }
}

optimizeImages();
