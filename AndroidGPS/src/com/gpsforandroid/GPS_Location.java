package com.gpsforandroid;

public class GPS_Location {
private double latitude, longitude;

public double getLatitude() {
	return latitude;
}

public void setLatitude(double latitude) {
	this.latitude = latitude;
}

public double getLongitude() {
	return longitude;
}

public void setLongitude(double longitude) {
	this.longitude = longitude;
}

public GPS_Location(double latitude, double longitude) {
	super();
	this.latitude = latitude;
	this.longitude = longitude;
}

public GPS_Location() {
	super();
	// TODO Auto-generated constructor stub
}

}
