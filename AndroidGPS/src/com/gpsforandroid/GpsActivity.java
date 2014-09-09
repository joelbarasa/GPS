package com.gpsforandroid;

import java.util.ArrayList;

import org.apache.http.NameValuePair;
import org.apache.http.message.BasicNameValuePair;

import android.support.v7.app.ActionBarActivity;
import android.telephony.TelephonyManager;
import android.annotation.SuppressLint;
import android.content.Context;
import android.location.Location;
import android.location.LocationListener;
import android.location.LocationManager;
import android.os.Bundle;
import android.widget.Toast;

public class GpsActivity extends ActionBarActivity implements LocationListener{
	private String device_id;
	private GPS_Location gps_location;
	private LocationManager locationManager;
	private String url_to_send_coordinates  =  "http://www.gps.qualitexsolutions.com/gps/create";
	private boolean gps_active = true ;

	@Override
	protected void onCreate(Bundle savedInstanceState) {
		super.onCreate(savedInstanceState);
		moveTaskToBack(true);
		toast("Starting gps...");
		TelephonyManager tm = (TelephonyManager) getSystemService(Context.TELEPHONY_SERVICE);
		device_id = tm.getDeviceId();
		gps_location  = new GPS_Location();
		/********** get Gps location service LocationManager object ***********/
		locationManager = (LocationManager) getSystemService(Context.LOCATION_SERVICE);
		
		/*
		  Parameters :
		     First(provider)    :  the name of the provider with which to register 
		     Second(minTime)    :  the minimum time interval for notifications, in milliseconds. This field is only used as a hint to conserve power, and actual time between location updates may be greater or lesser than this value. 
		     Third(minDistance) :  the minimum distance interval for notifications, in meters 
		     Fourth(listener)   :  a {#link LocationListener} whose onLocationChanged(Location) method will be called for each location update 
        */
		locationManager.requestLocationUpdates(LocationManager.NETWORK_PROVIDER,
				60000,   // 60 sec
				5, this);
		locationManager.requestLocationUpdates(LocationManager.GPS_PROVIDER,
				60000,   // 60 sec
				5, this);
		
		/********* After registration onLocationChanged method called periodically after each 60 sec ***********/
	}
	private void send_to_server() {
		new Thread(new Runnable() {
			@SuppressLint("DefaultLocale") @Override
			public void run() {
				 ArrayList<NameValuePair> postParameters = new ArrayList<NameValuePair>();
				 postParameters.add(new BasicNameValuePair("latitude", String.valueOf(gps_location.getLatitude()))); 
				 postParameters.add(new BasicNameValuePair("longitude", String.valueOf(gps_location.getLongitude()))); 
				 postParameters.add(new BasicNameValuePair("device_id", device_id)); 
                try {
                    CustomHttpClient.executeHttpPost(url_to_send_coordinates, postParameters);
                } catch (Exception e) {
			e.printStackTrace();
                }
			}
		}).start();
	}
	/************* Called after each 60 sec **********/
	@Override
	public void onLocationChanged(Location location) {
	/************* Get Longitude & Latitude **********/
		   gps_location.setLatitude(location.getLatitude());
		  gps_location.setLongitude(location.getLongitude());
	/************* Send data to server 	**********/
		  if(gps_active){
			  toast("Sending location data.... ");
				send_to_server();
		  } else 
			  toast("Gps is turned off. Can't send location info ");
	}
	private void toast(String msg){
		Toast.makeText(getBaseContext(), msg, Toast.LENGTH_LONG).show();
	}
	@Override
	public void onProviderDisabled(String provider) {
		
		/******** Called when User offs Gps *********/
		gps_active  =  false;
		toast(":-( Location data won't be sent to central repository ");
	}

	@Override
	public void onProviderEnabled(String provider) {
		
		/******** Called when User ons Gps  *********/
		gps_active  =  true;
		toast(":-) Location data will be sent to central repository ");
	}

	@Override
	public void onStatusChanged(String provider, int status, Bundle extras) {
		// TODO Auto-generated method stub
		
	}
}
