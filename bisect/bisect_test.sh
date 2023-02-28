#!/bin/sh


set -x

if [ "1" -gt "$#" ]; then
    echo "Usage bisect_test.sh SHA"
    exit 2
fi
SHA=$1
echo "SHA is $1"

# git checkout $SHA

#if [ $? -ne "0" ]; then
# echo "checkout of $SHA failed. That seems bad."
# echo "$SHA: checkout of $SHA failed. That seems bad." >> results.txt
# exit 1;
#fi;

cd /var/app/bisect

rm -rf *.tar.gz
rm -rf ImageMagick*

im_tgz_file="ImageMagick-${SHA}.tar.gz"

wget "https://github.com/ImageMagick/ImageMagick/archive/${SHA}.tar.gz" -O ${im_tgz_file}

sha_dir="ImageMagick-${SHA}"

tar xfz ${im_tgz_file}
cd $sha_dir




# rm makefile
rm /usr/local/bin/MagickCore-config
rm /usr/local/bin/MagickWand-config
rm /usr/local/bin/Magick-config
rm /usr/local/bin/Wand-config

./configure \
  --disable-docs \
  --with-quantum-depth=16 \
  --with-fftw \
  --with-fontconfig=yes \
  --with-jpeg=yes \
  --with-magick-plus-plus=no \
  --with-png=yes \
  --with-tiff=yes \
  --with-webp=yes \
  --without-perl \
  --enable-hdri=yes \
  --without-jxl \
  --without-zstd



if [ ! -f makefile ]
then
	echo "ImageMagick makefile not found, build is bad?"
	echo "$SHA: ImageMagick makefile not found, build is bad?" >> results.txt
	exit 3;
fi


make install -j30

if [ $? -ne "0" ]; then
  echo "ImageMagick make failed, build is bad?"
  echo "$SHA: ImageMagick make failed, build is bad?" >> results.txt
  exit 4;
fi;

cd /var/app
sh ./configure

if [ $? -ne "0" ]; then
  echo "Imagick compile failed, build is bad?"
  echo "$SHA:Imagick compile failed, build is bad?" >> results.txt
  exit 5;
fi;

make clean
make install -j16

if [ $? -ne "0" ]; then
  echo "Imagick compile failed, build is bad?"
  echo "$SHA: Imagick compile failed, build is bad?" >> results.txt
  exit 6;
fi;

cd /var/app

php /var/app/bugs/gh580/debug.php

if [ $? -ne "0" ]; then
  echo "Version seems to show bug."
  echo "$SHA: Version seems to show bug." >> results.txt
  exit 6;
else
  echo "Version seems fine."
  echo "$SHA: Version seems fine." >> results.txt
fi;

exit $?
